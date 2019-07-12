<?php
  /******************************************************************************
  * $Id: doc_Bodegas_E008.class.php,v 1.2 2010/08/31 22:04:25 hugo Exp $
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

class doc_bodegas_E008
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
         var_dump($ClassDOC->ErrMsg());
       }
  }
  function ConsultarItemsExistencias($Codigo_Producto,$Empresa_Id,$Centro_Utilidad,$Bodega,$lote,$fecha_vencimiento)
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
                      and fecha_vencimiento='".$fecha_vencimiento."'
                      and lote='".$lote."'
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
  function SacarProductosFarmacia($doc_tmp_id,$usuario_id,$numero_pedido,$empresa_id,$param,$aumento)
   {
     $sql .= " SELECT DISTINCT e.fecha_vencimiento,
                               e.codigo_producto,
                               e.lote,
                               round(e.existencia_actual) as existencia_actual,
                               a.solicitud_prod_a_bod_ppal_id,
                               round(a.cantidad_solic) as cantidad_solic, 	
                               fc_descripcion_producto(a.codigo_producto) as descripcion,
							   b.sw_requiereautorizacion_despachospedidos,
                               b.unidad_id,
                               c.descripcion as descripcion_unidad,
                               d.costo as costo,
                               IV.sw_lunes||':'||IV.sw_martes||':'||IV.sw_miercoles||':'||IV.sw_jueves||':'||IV.sw_viernes||':'||IV.sw_sabado||':'||IV.sw_domingo AS dias_envio
                FROM           solicitud_productos_a_bodega_principal_detalle as a
							   JOIN solicitud_productos_a_bodega_principal AS f ON (a.solicitud_prod_a_bod_ppal_id = f.solicitud_prod_a_bod_ppal_id),
                               inventarios_productos as b,
                               unidades as c,
                               inventarios d, 
                               existencias_bodegas_lote_fv e,
                               inv_dias_envio_tipos_productos IV
                WHERE          a.solicitud_prod_a_bod_ppal_id=".$numero_pedido."
                AND            b.codigo_producto = a.codigo_producto
                AND            b.estado = '1'
                AND            f.sw_despacho = '0'
                AND            c.unidad_id = b.unidad_id
                AND            d.empresa_id = '".$empresa_id."'
                AND            d.codigo_producto = a.codigo_producto 
                AND            d.empresa_id=e.empresa_id
                AND            d.codigo_producto=e.codigo_producto
                AND            a.sw_pendiente='0'
                AND            e.existencia_actual > 0
                AND            b.tipo_producto_id = IV.tipo_producto_id
                AND            IV.empresa_id = '".$empresa_id."'
                AND            e.estado = '1' 
                ".$aumento."
                ORDER BY b.sw_requiereautorizacion_despachospedidos ASC,e.fecha_vencimiento ASC,e.codigo_producto,round(e.existencia_actual),e.lote,dias_envio ";
				/*AND            b.sw_requiereautorizacion_despachospedidos = '0'*/
     
     /*print_r($sql);*/
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[$resultado->fields[1]][$resultado->fields[6]][] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  }
    function SacarProductosFarmaciaSW($empresa_id,$aumento,$numero_pedido)
    {
      $sql .= " SELECT  a.codigo_producto,
                        a.solicitud_prod_a_bod_ppal_id,
                        round(FR.cantidad_pendiente) AS cantidad_solic,  	
                        fc_descripcion_producto(a.codigo_producto) as descripcion,
						b.sw_requiereautorizacion_despachospedidos,
                        b.unidad_id,
                        c.fecha_vencimiento,
                        c.lote,
						inv.costo,
                       round(c.existencia_actual) as existencia_actual,
                        IV.sw_lunes||':'||IV.sw_martes||':'||IV.sw_miercoles||':'||IV.sw_jueves||':'||IV.sw_viernes||':'||IV.sw_sabado||':'||IV.sw_domingo AS dias_envio
                FROM    solicitud_productos_a_bodega_principal_detalle as a,
                        inv_mov_pendientes_solicitudes_frm FR,
                        inventarios_productos as b,
                        existencias_bodegas_lote_fv c
						JOIN inventarios as inv ON (c.codigo_producto = inv.codigo_producto)
						AND(c.empresa_id = inv.empresa_id),
                        inv_dias_envio_tipos_productos IV
                WHERE     b.codigo_producto = a.codigo_producto
                AND       b.estado = '1'
                AND       b.codigo_producto = c.codigo_producto
                AND       c.empresa_id = '".$empresa_id."'
                AND       c.existencia_actual > 0
                ".$aumento."
                AND    a.solicitud_prod_a_bod_ppal_id = ".$numero_pedido."
                AND    a.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id
                AND    a.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id
                AND    b.tipo_producto_id = IV.tipo_producto_id
                AND    IV.empresa_id = '".$empresa_id."'
  		          AND            c.estado = '1' 
                ORDER BY b.sw_requiereautorizacion_despachospedidos ASC,c.fecha_vencimiento ASC,c.codigo_producto,c.existencia_actual,c.lote,dias_envio ";
     
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       
       $documentos[$resultado->fields[0]][$resultado->fields[3]][]= $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    //print_r($documentos);
      $resultado->Close();
     //return $sql;
      return $documentos;
  }
  
    function SacarProductosFarmaciaS($empresa_id,$codigo_producto)
    {
      $sql .= " SELECT  a.codigo_producto,
                         a.solicitud_prod_a_bod_ppal_id,
                         FR.cantidad_solicitad AS cantidad_solic, 	
                         b.descripcion,b.unidad_id,
                         a.fecha_vencimiento,
                         a.lote,
                         c.existencia_actual,
                         IV.sw_lunes||':'||IV.sw_martes||':'||IV.sw_miercoles||':'||IV.sw_jueves||':'||IV.sw_viernes||':'||IV.sw_sabado||':'||IV.sw_domingo AS dias_envio
                  FROM   solicitud_productos_a_bodega_principal_detalle as a,
                         inv_mov_pendientes_solicitudes_frm FR,
                         inventarios_productos as b,
                         existencias_bodegas_lote_fv c,
                         inv_dias_envio_tipos_productos IV
                  WHERE  a.codigo_producto = '".$codigo_producto."'
                  AND    b.codigo_producto = a.codigo_producto
                  AND    b.codigo_producto = a.codigo_producto
                  AND    b.codigo_producto = c.codigo_producto
                  AND    c.empresa_id = '".$empresa_id."'
                  AND    c.fecha_vencimiento = a.fecha_vencimiento
                  AND    c.lote = a.lote
                  AND    c.existencia_actual > 0
                  AND    a.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id
                  AND    a.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id
                  AND    b.tipo_producto_id = IV.tipo_producto_id
                  AND    IV.empresa_id = '".$empresa_id."'
    		  AND            c.estado = '1' 
                  ORDER BY c.codigo_producto,c.existencia_actual,c.fecha_vencimiento,c.lote,dias_envio ";
		/*print_r($sql);*/
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
    
      $resultado->Close();
      return $documentos;
    }

    function ConsultarItems($doc_tmp_id,$bodegas_doc_id)
		{
			$ClassDOC = new BodegasDocumentos($bodegas_doc_id);
      $OBJETO = $ClassDOC->GetOBJ();
			$RETORNO = $OBJETO->GetItemsDocTemporal($doc_tmp_id);
      
      if(!$RETORNO)
      {
        echo $this->mensajeDeError = $ClassDOC->frmError['MensajeError'];
        return false;
      }
      
      return $RETORNO;
		}    
    /**
    * Funcion donde se obtienen los items del documento temporal, agrupados
    *
    * @return bollean
    */
    function ConsultarItemsAgrupados($doc_tmp_id,$bodegas_doc_id)
		{
			$ClassDOC = new BodegasDocumentos($bodegas_doc_id);
      $OBJETO = $ClassDOC->GetOBJ();
			$RETORNO = $OBJETO->GetItemsDocTemporalAgrupado($doc_tmp_id);

      if(!$RETORNO)
      {
        $this->mensajeDeError = $ClassDOC->frmError['MensajeError'];
        return false;
      }
      
      return $RETORNO;
		}
		
	function ConsultarItemsAutorizar($doc_tmp_id,$bodegas_doc_id)
	{
		$sql  = "SELECT
		a.doc_tmp_id,
		a.usuario_id,
		a.empresa_id,
		a.centro_utilidad,
		a.bodega,
		a.codigo_producto,
		a.lote,
		a.fecha_vencimiento,
		a.cantidad,
		a.porcentaje_gravamen,
		a.total_costo,
		'<b class=\"label_error\" title=\"ITEM POR AUTORIZAR\">X</b>' AS mensaje
		FROM
		inv_bodegas_movimiento_tmp_autorizaciones_despachos AS a
		WHERE TRUE 
		AND doc_tmp_id = '".trim($doc_tmp_id)."'
		AND usuario_id = '".UserGetUID()."'
		AND sw_autorizado = '0';";
	//print_r($sql);
	if(!$rst = $this->ConexionBaseDatos($sql))
	return false;

	$datos = array();
	while(!$rst->EOF)
    {
	$datos[$rst->fields[5]][$rst->fields[6]][$rst->fields[7]] = $rst->GetRowAssoc($ToUpper = false);
	$rst->MoveNext();
	}
	$rst->Close();

	return $datos;
	} 
	
		
    /**
    * Funcion donde se obtiene la informacion de los documentos de bodega
    *
    * @param integer $bodegas_doc_id Identificador del documento
    *
    * @return mixed;
    */
    function TraerDatos($bodegas_doc_id)
    {
      $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
      $OBJETO = $ClassDOC->GetOBJ();
      if(!$OBJETO)
      {
        $this->mensajeDeError = $ClassDOC->mensajeDeError;
        return false;
      }
      
      $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
      
      if(!is_object($ClassDOC))
      {
        die(MsgOut("Error al crear la clase","BodegasDocumentos"));
      }
      return $RETORNO;
    }
    
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$offset)
    {
      $sql="SELECT 
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  e.existencia_actual AS existencia,
                  d.costo,
                  e.fecha_vencimiento,
                  e.lote
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d,
                  existencias_bodegas_lote_fv e
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento." 
              AND b.codigo_producto = a.codigo_producto
              AND b.estado = '1'
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto
              AND   a.empresa_id = e.empresa_id
              AND   a.centro_utilidad = e.centro_utilidad
              AND   a.bodega = e.bodega
              AND   a.codigo_producto = e.codigo_producto
              AND   e.existencia_actual>0  
              AND   e.estado = '1'
              ";
              
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



     function GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$total_costo_ped)
     {
      $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
      $OBJETO=$ClassDOC->GetOBJ();
      //AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$localizacion,$total_costo_ped)
      $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id=null,$fecha_venc,$lotec,null,$total_costo_ped);
       //var_dump($RETORNO);
      return $RETORNO;
     }
	 

	 
	function GuardarTemporalAutorizacion($empresa_id,$centro_utilidad,$bodega,$doc_tmp_id,$codigo,$can,$total_costo,$fecha_vencimiento,$lote,$porcentaje_gravamen=0)
	{
	/*$this->ConexionTransaccion();*/
	$fecha_vencim=explode("-",$fecha_vencimiento);
	$fechavencimiento=$fecha_vencim[2]."-".$fecha_vencim[1]."-".$fecha_vencim[0];
	$sql = " INSERT INTO  inv_bodegas_movimiento_tmp_autorizaciones_despachos(
	doc_tmp_id,
	usuario_id,
	empresa_id,
	centro_utilidad,
	bodega,
	codigo_producto,
	lote,
	fecha_vencimiento,
	cantidad,
	porcentaje_gravamen,
	total_costo)
	VALUES     (
	'".trim($doc_tmp_id)."',
	'".UserGetUID()."',
	'".trim($empresa_id)."',
	'".trim($centro_utilidad)."',
	'".trim($bodega)."',
	'".trim($codigo)."',
	'".trim($lote)."',
	'".trim($fechavencimiento)."',
	".trim($can).",
	".trim($porcentaje_gravamen).",
	".trim($total_costo)."
	)";

	if(!$resultado = $this->ConexionBaseDatos($sql))
	{
	$cad="Operacion Invalida";
	return false;//$cad;
	} 
	$resultado->Close();
	return true;
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
   
   function GuardarCaja($tmp_doc_id,$cliente,$direccion,$cantidad,$ruta,$descripcion,$solicitud_prod_a_bod_ppal_id)
  {
     //$this->ConexionTransaccion();
     
     $sql = " INSERT INTO  inv_rotulo_caja(
                           id_inv_rotulo_caja,              
                           documento_id,	
                           solicitud_prod_a_bod_ppal_id,
                           cliente,
                           direccion,
                           cantidad,
                           ruta,
                           contenido,
                           usuario_registro,
                           fecha_registro)
               VALUES     (default,
                           ".$tmp_doc_id.",
                           ".$solicitud_prod_a_bod_ppal_id.",
                           '".$cliente."',
                           '".$direccion."',
                           '".$cantidad."',
                           '".$ruta."',
                           '".$descripcion."',
                           ".UserGetUID().",
                           NOW() )";
      
         //print_r($sql);         
    if(!$resultado = $this->ConexionBaseDatos($sql))
    {
      $cad="Operacion Invalida";
      return false;//$cad;
    } 
    $resultado->Close();
    return true;
  }
     
    function ActuCaja($tmp_doc_id,$cliente,$direccion,$cantidad,$ruta,$descripcion,$solicitud_prod_a_bod_ppal_id)
    {
      $sql = "UPDATE inv_rotulo_caja
              SET    cliente='".$cliente."', direccion='".$direccion."', cantidad='".$cantidad."', contenido='".$descripcion."'             
              WHERE  solicitud_prod_a_bod_ppal_id='".$solicitud_prod_a_bod_ppal_id."'  ";
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      $resultado->Close();
      return true;
    }
    
    function Actualizar_ETrigger($empresa_id,$prefijo,$numero)
    {
      $sql = "UPDATE inv_bodegas_movimiento_despachos_farmacias
              SET    sw_revisado= sw_revisado
              WHERE
                        empresa_id = '".$empresa_id."' 
                    and prefijo = '".$prefijo."' 
                    and numero = ".$numero."  ";
       //print_r($sql); 
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      $resultado->Close();
      return true;
    }
    
      /**
    * Funcion donde se obtiene la informacion del rotulo de la caja
    *
    * @param integer $tmp_doc_id
    * @param integer $pedido
    *
    * @return array
    */
    function ObtenerSiEstaRotulo($tmp_doc_id)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   inv_rotulo_caja ";
      $sql .= "WHERE  documento_id = ".$tmp_doc_id." ";
     //print_r($sql);
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
              
      $datos = array();
      if(!$resultado->EOF)
      {
        $datos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
    
      $resultado->Close();
      return $datos;
    }   
    
    /**
    * Funcion donde se obtiene la informacion del rotulo de la caja
    *
    * @param integer $tmp_doc_id
    * @param integer $pedido
    *
    * @return array
    */
    function ObtenerDatosRotuloCaja($tmp_doc_id,$pedido)
    {
      $sql  = "SELECT id_inv_rotulo_caja,";
      $sql .= " 	    documento_id,";
      $sql .= " 	    solicitud_prod_a_bod_ppal_id,";
      $sql .= " 	    cliente AS nombre_tercero,";
      $sql .= " 	    direccion,";
      $sql .= " 	    cantidad,";
      $sql .= " 	    ruta,";
      $sql .= " 	    contenido,";
      $sql .= " 	    usuario_registro,";
      $sql .= " 	    fecha_registro ";
      $sql .= "FROM   inv_rotulo_caja ";
      $sql .= "WHERE  solicitud_prod_a_bod_ppal_id = ".$pedido." ";
      $sql .= "AND    documento_id = ".$tmp_doc_id." ";

      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
              
      $datos = array();
      if(!$resultado->EOF)
      {
        $datos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
    
      $resultado->Close();
      return $datos;
    }    
    /**
    *
    */
    function ConsulCajaRotulo($tmp_doc_id,$pedido,$identificador)
    {
      if($identificador == "FM")
      {
        $sql  = "SELECT EM.tipo_id_tercero,";
        $sql .= " 	    EM.id AS tercero_id, ";
        $sql .= "       EM.razon_social AS nombre_tercero, ";
        $sql .= "       EM.direccion ";
        $sql .= "FROM   inv_bodegas_movimiento_tmp_despachos_farmacias b, ";
        $sql .= "       empresas EM ";
        $sql .= "WHERE  b.doc_tmp_id = ".$tmp_doc_id." ";
        $sql .= "AND    b.solicitud_prod_a_bod_ppal_id = ".$pedido['solicitud_prod_a_bod_ppal_id']." ";
        $sql .= "AND    EM.empresa_id = b.farmacia_id ";
      }
      else if($identificador == "CL")
      {
        $sql  = "SELECT TE.tipo_id_tercero,";
        $sql .= " 	    TE.tercero_id,";
        $sql .= "       TE.nombre_tercero, ";
        $sql .= "       TE.direccion ";
        $sql .= "FROM   inv_bodegas_movimiento_tmp_despachos_clientes b, ";
        $sql .= "       terceros TE ";
        $sql .= "WHERE  b.tipo_id_tercero = TE.tipo_id_tercero ";
        $sql .= "AND    b.tercero_id = TE.tercero_id ";
        $sql .= "AND    b.doc_tmp_id = ".$tmp_doc_id." ";
        $sql .= "AND    b.pedido_cliente_id = ".$pedido['pedido_cliente_id']." ";
      }

      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
              
      $datos = array();
      if(!$resultado->EOF)
      {
        $datos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
    
      $resultado->Close();
      return $datos;
    }
    /**
    *
    */
    function ObtenerInformcionRotulo($tmp_doc_id,$pedido)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   inv_rotulo_caja ";
      $sql .= "WHERE  documento_id = ".$tmp_doc_id." ";
      $sql .= "AND    solicitud_prod_a_bod_ppal_id = ".$pedido." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      if(!$resultado->EOF)
      {
        $datos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
    
      $resultado->Close();
      return $datos;
    }
  
  
  function ConsulPedidoDetalle($CodigoProducto,$num_pedido)
  {
    //$this->debug=true;
    $sql  = "SELECT		solicitud_prod_a_bod_ppal_det_id, 	
								solicitud_prod_a_bod_ppal_id, 	
								farmacia_id, 	
								centro_utilidad, 	
								bodega, 	
								codigo_producto, 	
								round(cantidad_solic) as cantidad_solic, 	
								tipo_producto, 	
								usuario_id, 	
								fecha_registro, 	
								fecha_vencimiento, 	
								lote, 	
								sw_pendiente, 	
								observacion ";
    $sql .= "FROM		solicitud_productos_a_bodega_principal_detalle ";
    $sql .= "WHERE	solicitud_prod_a_bod_ppal_id=".$num_pedido." ";
    $sql .= "AND		codigo_producto='".$CodigoProducto."' ";
    
    
    //print_r($sql);
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     //$documentos=Array();
     if(!$resultado->EOF)
     {
       $documentos = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  }
   function ConsulPedidoSw($CodigoProducto,$num_pedido)
  {
    //$this->debug=true;
    $sql  = "SELECT		solicitud_prod_a_bod_ppal_det_id, 	
								solicitud_prod_a_bod_ppal_id, 	
								farmacia_id, 	
								centro_utilidad, 	
								bodega, 	
								codigo_producto, 	
								round(cantidad_solic) as cantidad_solic, 	
								tipo_producto, 	
								usuario_id, 	
								fecha_registro, 	
								fecha_vencimiento, 	
								lote, 	
								sw_pendiente, 	
								observacion ";
    $sql .= "FROM		solicitud_productos_a_bodega_principal_detalle ";
    $sql .= "WHERE	solicitud_prod_a_bod_ppal_id=".$num_pedido." ";
    $sql .= "AND		codigo_producto='".$CodigoProducto."' ";
    $sql .= "AND		sw_pendiente=1 ";
    
    
    //print_r($sql);
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
  
   function ConsulPedidoSwMostrar($codigo_producto,$fecha_vencimiento,$lote)
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		solicitud_productos_a_bodega_principal_detalle ";
    $sql .= "WHERE	codigo_producto='".$codigo_producto."' ";
    $sql .= "AND		fecha_vencimiento='".$fecha_vencimiento."' ";
    $sql .= "AND    lote='".$lote."' ";
    $sql .= "AND		sw_pendiente=1 ";
    
    
    //print_r($sql);
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
    /*
    * Funcion de Modificacion de un producto en la orden de Compra,  cuando un producto se ha ingresado, modiique las cantidades
    *  del lote original.
    */
    function ActuRuta($tmp_doc_id,$ruta,$tabla)
    {
       
	    $sql  = "UPDATE inv_bodegas_movimiento_tmp_despachos_".$tabla." ";
      $sql .= "SET    rutaviaje_destinoempresa_id = '".$ruta."'";
      $sql .= "WHERE  doc_tmp_id 	 = ".$tmp_doc_id." ";
            
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
      $result->Close();
      return true;
    }   
    /*
    * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
    * llegue con diferentes lotes.
    */
    function IngresarProductoPedido($CodigoProducto,$Cantidades,$num_pedido,$farmacia_id,$centro_utilidad,$bodega,$tipo_producto,$lote,$fecha_venc)
    {
              
        $sql  = "INSERT INTO solicitud_productos_a_bodega_principal_detalle (";
        $sql .= "       solicitud_prod_a_bod_ppal_det_id, ";
        $sql .= "       solicitud_prod_a_bod_ppal_id, ";
        $sql .= "       farmacia_id     , ";
        $sql .= "       centro_utilidad     , ";
        $sql .= "       bodega,     ";
        $sql .= "       codigo_producto,     ";
        $sql .= "       cantidad_solic,     ";
        $sql .= "       tipo_producto,     ";
        $sql .= "       usuario_id,     ";
		    $sql .= "       fecha_registro,    ";
        $sql .= "       fecha_vencimiento,     ";
        $sql .= "       lote,     ";
        $sql .= "       sw_pendiente";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        default,";
        $sql .= "        '".$num_pedido."', ";
        $sql .= "        '".$farmacia_id."', ";
        $sql .= "        '".$centro_utilidad."', ";
        $sql .= "        '".$bodega."', ";
        $sql .= "        '".$CodigoProducto."', ";
        $sql .= "        '".$Cantidades."', ";
        $sql .= "        '".$tipo_producto."', ";
        $sql .= "        '".UserGetUID()."', ";
		    $sql .= "        NOW(), ";
        $sql .= "        '".$fecha_venc."', ";
        $sql .= "        '".$lote."', ";
        $sql .= "        '1' ";
        $sql .= "       ); ";	
			//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    $resultado->Close();
         return true;
			
    }
    /*
    * Funcion donde se obtiene el listado de terceros
    *
    * @param integer $pagina pagina actual
    * @param string $tipo_id Tipo id tecero a buscar
    * @param string $id Identificacion del tercero a buscar
    * @param string $nombre Nombre del tercero a buscar
    *
    * @return mixed
    */
	  function ObtenerTerceros($pagina,$tipo_id,$id,$nombre)    
    {
      
		$sql  = "SELECT DISTINCT
		a.*,
		c.descripcion as bloqueo
		FROM
		terceros as a
		JOIN ventas_ordenes_pedidos as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
		AND (a.tercero_id = b.tercero_id)
		AND (b.estado = '1')
		AND (b.empresa_id = '".SessionGetVar("EMPRESA")."')
		LEFT JOIN inv_tipos_bloqueos as c ON (a.tipo_bloqueo_id = c.tipo_bloqueo_id) 
		WHERE ";
		$sql .= " a.nombre_tercero ILIKE '%".$nombre."%'";
		if($id)
        $sql .= " AND   a.tercero_id='".$id."' ";
		if($tipo_id != "-1" && $tipo_id)
        $sql .= " AND   a.tipo_id_tercero='".$tipo_id."' ";
      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$pagina);     
      
      $sql .= " ORDER BY a.nombre_tercero ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";   
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;    
      
      $cuentas=array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
        
      $resultado->Close();
      return $cuentas;
    }
    /*
    * Funcion donde se obtiene el listado de documentos del cliente
    *
    * @param string $empresa_id
    * @param string $tipo_id Tipo id tecero a buscar
    * @param string $tercero_id Identificacion del tercero a buscar
    *
    * @return mixed
    */
	  function ObtenerPedidosClientes($empresa_id,$tipo_id,$tercero_id)    
    {
      $sql  = "SELECT VP.pedido_cliente_id ";
      $sql .= "FROM   ventas_ordenes_pedidos VP ";
      $sql .= "WHERE  VP.estado = '1' ";
      $sql .= "AND    VP.tercero_id='".$tercero_id."' ";
      $sql .= "AND    VP.tipo_id_tercero='".$tipo_id."' ";
      $sql .= "ORDER BY pedido_cliente_id ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;    
      
      $datos =array();
      while(!$resultado->EOF)
      {
        $datos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
        
      $resultado->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtienen los tipos de identificacion de los terceros
    *
    * @return mixed
    */
    function ObtenerTiposTerceros()
    { 
      $sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
      $sql .= "ORDER BY indice_de_orden "; 
      
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
    * Funcion dnde se crea el documento temporal de despachos
    *
    * @return mixed
    */
    function CrearDoc($bodegas_doc_id, $observacion, $tipo_id_farmaClie,$pedido_farmacia, $tipo_id_tercero,$tercero_id, $farmacia_id)
    {
      $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
      $OBJETO=$ClassDOC->GetOBJ();
      
      $RETORNO = $OBJETO->NewDocTemporal($observacion, $tipo_id_farmaClie,$pedido_farmacia, $tipo_id_tercero,$tercero_id, $farmacia_id,UserGetUID());
      if(!$RETORNO)
      {
        $this->mensajeDeError = $OBJETO->mensajeDeError;
        return false;
      }
      return $RETORNO;
    }
	/**
	*
	*/
    function ObtenerProductosPedidoCliente($doc_tmp_id,$usuario_id,$numero_pedido,$empresa_id,$param,$aumento,$centro_utilidad,$bodega)
    {
		
		$sql = " SELECT
		a.codigo_producto,
		fc_descripcion_producto(a.codigo_producto) as descripcion,
		b.sw_requiereautorizacion_despachospedidos,
		a.numero_unidades,
		a.cantidad_despachada,
		a.porc_iva,
		a.valor_unitario,
		a.pedido_cliente_id,
		(a.numero_unidades-a.cantidad_despachada) as cantidad_total,
		(((COALESCE(a.numero_unidades,0)-COALESCE(a.cantidad_despachada,0))-COALESCE(c.cantidad,0))-COALESCE(g.cantidad,0)) as cantidad_pendiente,
		(((COALESCE(a.numero_unidades,0)-COALESCE(a.cantidad_despachada,0))-COALESCE(c.cantidad,0))) as cantidad_pendiente_,
		e.observacion,
		f.existencia,
		CASE 
		when g.codigo_producto IS NOT NULL 
		THEN '<b class=\"label_error\" title=\"ITEM POR AUTORIZAR\">X</b>'
		ELSE '' END as mensaje
		FROM
		ventas_ordenes_pedidos_d AS a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		AND (a.numero_unidades <> a.cantidad_despachada)
		LEFT JOIN (
					SELECT
					codigo_producto,
					sum(cantidad)AS cantidad,
					observacion_cambio
					FROM
					inv_bodegas_movimiento_tmp_d
					WHERE
					doc_tmp_id = ".trim($doc_tmp_id)."
					AND usuario_id = ".UserGetUID()."
					GROUP BY codigo_producto,observacion_cambio
		) as c ON (a.codigo_producto = c.codigo_producto)
		JOIN ventas_ordenes_pedidos AS d ON (a.pedido_cliente_id = d.pedido_cliente_id)
		AND (d.estado = '1')
		AND (d.empresa_id = '".trim($empresa_id)."')
		LEFT JOIN (
					SELECT
					codigo_producto,
					observacion
					FROM
					inv_bodegas_movimiento_tmp_justificaciones_pendientes
					WHERE
					doc_tmp_id = ".trim($doc_tmp_id)."
					AND usuario_id = ".UserGetUID()."
		) as e ON (a.codigo_producto = e.codigo_producto)
		JOIN existencias_bodegas as f ON (a.codigo_producto = f.codigo_producto)
		LEFT JOIN (
						SELECT
						round(SUM(cantidad)) as cantidad,
						codigo_producto
						FROM
						inv_bodegas_movimiento_tmp_autorizaciones_despachos
						WHERE TRUE
						AND sw_autorizado = '0'
						AND doc_tmp_id = '".trim($doc_tmp_id)."'
						AND usuario_id = '".UserGetUID()."'
						GROUP BY codigo_producto
						) as g ON (a.codigo_producto = g.codigo_producto)
		WHERE
		a.pedido_cliente_id = ".trim($numero_pedido)." ";
		$sql .= "AND f.empresa_id = '".trim($empresa_id)."'";
		$sql .= "AND f.centro_utilidad = '".trim($centro_utilidad)."'";
		$sql .= "AND f.bodega = '".trim($bodega)."'";
		$sql .= $aumento;
		$sql .= "	ORDER BY 	b.sw_requiereautorizacion_despachospedidos ASC,b.descripcion ASC";
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
	
	
	/*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
	function Consultar_ExistenciasBodegas($codigo_producto,$empresa_id,$centro_utilidad,$bodega,$doc_tmp_id)
	{
	//	$this->debug=true;
	$sql = "SELECT DISTINCT
	a.*,
	CASE
	WHEN b.item_id IS NOT NULL OR
	d.codigo_producto IS NOT NULL
	THEN ' disabled checked '
	ELSE ' '
	END as bloqueo,
	sw_requiereautorizacion_despachospedidos,
	CASE 
	when d.codigo_producto IS NOT NULL 
	THEN '<b class=\"label_error\" title=\"ITEM POR AUTORIZAR\">X</b>'
	ELSE '' END as mensaje
	FROM
	existencias_bodegas_lote_fv AS a
	LEFT JOIN inv_bodegas_movimiento_tmp_d as b ON (a.codigo_producto = b.codigo_producto)
	AND (a.lote = b.lote)
	AND (a.fecha_vencimiento = b.fecha_vencimiento)
	AND (b.doc_tmp_id =".$doc_tmp_id.")
	AND (b.usuario_id =".UserGetUID().") 
	JOIN inventarios_productos as c ON (a.codigo_producto = c.codigo_producto) 
	LEFT JOIN  inv_bodegas_movimiento_tmp_autorizaciones_despachos as d ON 
	(a.codigo_producto = d.codigo_producto)
	AND (a.lote = d.lote)
	AND (a.fecha_vencimiento = d.fecha_vencimiento)
	AND (d.sw_autorizado = '0')
	AND (d.doc_tmp_id = '".trim($doc_tmp_id)."')
	AND (d.usuario_id = '".UserGetUID()."')	";
	
	$sql .= " 		WHERE ";
	$sql .= "           a.codigo_producto = '".$codigo_producto."'  ";
	$sql .= "      and  a.empresa_id = '".$empresa_id."' ";
	$sql .= "      and  a.centro_utilidad = '".$centro_utilidad."' ";
	$sql .= "      and  a.bodega = '".$bodega."' ";
	$sql .= "      and  a.existencia_actual > 0 ";
	$sql .= "      and  a.estado = '1' ";
	$sql .= " ORDER BY a.fecha_vencimiento ASC ";

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
	
    /**
    * Funcion donde se elimina un item de la lista
    *
    * @return mixed
    */
    function RemoverItem($bodegas_doc_id,$item)
    {
      $ClassDOC = new BodegasDocumentos();
      $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);
      $resultado = $OBJETO->DelItemDocTemporal($item);
      if(!$resultado)
      {
        $this->mensajeDeError = $OBJETO->mensajeDeError;
        return false;
      }
      return true;
    }
    /**
    *
    */
    /*function ObtnerCantidadIngresada($Codigo_Producto,$doc_tmp_id)
		{
      $sql  = "SELECT SUM(TM.cantidad) AS cantidad, ";
      $sql .= "       SC.descripcion ";
      $sql .= "FROM	  inv_bodegas_movimiento_tmp_d TM, ";
      $sql .= "       inventarios_productos IV, ";
      $sql .= "       (";
      $sql .= "         SELECT II.subclase_id, ";
      $sql .= "                II.descripcion ";
      $sql .= "         FROM   inventarios_productos IV, ";
      $sql .= "                inv_subclases_inventarios II ";
      $sql .= "         WHERE  IV.codigo_producto = '".$Codigo_Producto."' ";
      $sql .= "         AND    IV.grupo_id = II.grupo_id ";
      $sql .= "         AND    IV.clase_id = II.clase_id ";
      $sql .= "         AND    IV.subclase_id = II.subclase_id ";
      $sql .= "       ) SC ";
      $sql .= "WHERE  TM.doc_tmp_id = ".$doc_tmp_id." ";
      $sql .= "AND    TM.codigo_producto = IV.codigo_producto	";
      $sql .= "AND    IV.subclase_id = SC.subclase_id ";
      $sql .= "GROUP BY SC.descripcion ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
			$rst->Close();

			return $datos;
		}    */
	/*function ObtnerCantidadIngresada($Codigo_Producto,$doc_tmp_id)
	{
	$sql  = "SELECT round(SUM(TM.cantidad)) AS cantidad, ";
	$sql .= "       IV.descripcion ";
	$sql .= "FROM	inv_bodegas_movimiento_tmp_d TM, ";
	$sql .= "       inventarios_productos IV ";
	$sql .= "WHERE  TM.doc_tmp_id = ".$doc_tmp_id." ";
	$sql .= "AND	TM.usuario_id = ".UserGetUID()."";
	$sql .= "AND	TM.codigo_producto = '".$Codigo_Producto."' ";
	$sql .= "AND    TM.codigo_producto = IV.codigo_producto	";
	$sql .= "GROUP BY (IV.descripcion) ";
	//print_r($sql);
	if(!$rst = $this->ConexionBaseDatos($sql))
	return false;

	$datos = array();
	if(!$rst->EOF)
	{
	$datos = $rst->GetRowAssoc($ToUpper = false);
	$rst->MoveNext();
	}
	$rst->Close();

	return $datos;
	}*/ 
	
	function ObtnerCantidadIngresada($Codigo_Producto,$doc_tmp_id)
	{
		$sql .= "SELECT 
				round(SUM(A.cantidad))as cantidad,
				A.codigo_producto,
				fc_descripcion_producto(A.codigo_producto) as descripcion";
		$sql .= " FROM   ( ";
		$sql .= "         SELECT  
							round(SUM(TM.cantidad)) AS cantidad, ";
		$sql .= "        TM.codigo_producto ";
		$sql .= "         FROM	  	inv_bodegas_movimiento_tmp_d TM ";
		$sql .= "         WHERE  TM.doc_tmp_id = '".trim($doc_tmp_id)."' 
							AND TM.usuario_id = '".UserGetUID()."' 
							AND TM.codigo_producto = '".trim($Codigo_Producto)."' ";
		$sql .= "         GROUP BY TM.codigo_producto ";
		$sql .= "		UNION ";
		$sql .= "		SELECT
							round(SUM(cantidad)) as cantidad,
							codigo_producto
							FROM
							inv_bodegas_movimiento_tmp_autorizaciones_despachos
							WHERE TRUE
							AND sw_autorizado = '0'
							AND doc_tmp_id = '".trim($doc_tmp_id)."'
							AND usuario_id = '".UserGetUID()."'
							AND codigo_producto = '".trim($Codigo_Producto)."'
							GROUP BY codigo_producto ";
		$sql .= "       		) A	";
		$sql .= " group by A.codigo_producto ";
	//print_r($sql);
	if(!$rst = $this->ConexionBaseDatos($sql))
	return false;

	$datos = array();
	if(!$rst->EOF)
	{
	$datos = $rst->GetRowAssoc($ToUpper = false);
	$rst->MoveNext();
	}
	$rst->Close();

	return $datos;
	}
	

		function ObtnerCantidadSolicitada($Codigo_Producto,$doc_tmp_id,$identificador)
		{
		if($identificador == 'FM')
		{
		$sql  = " SELECT round(SUM(solicitud)) AS solicitud
		FROM
		(
		SELECT  CASE WHEN FR.cantidad_pendiente IS NOT NULL THEN cantidad_pendiente
		ELSE    SD.cantidad_solic END AS solicitud
		FROM	  inventarios_productos IV,
				inv_bodegas_movimiento_tmp_despachos_farmacias DF,
				solicitud_productos_a_bodega_principal_detalle SD
		LEFT JOIN inv_mov_pendientes_solicitudes_frm FR
		ON 
		(
		SD.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id AND
		SD.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id
		)
		WHERE   DF.doc_tmp_id = ".$doc_tmp_id."
		AND		DF.usuario_id = ".UserGetUID()."
		AND     DF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id
		AND		SD.codigo_producto = '".$Codigo_Producto."'
		AND     SD.codigo_producto = IV.codigo_producto
		) A ";
		}
		else
		{
		$sql = "SELECT  SUM(SD.numero_unidades) AS solicitud
		FROM	  inventarios_productos IV,
		inv_bodegas_movimiento_tmp_despachos_clientes DF,
		ventas_ordenes_pedidos_d SD
		WHERE   DF.doc_tmp_id = ".$doc_tmp_id."
		AND		DF.usuario_id =".UserGetUID()."
		AND     DF.pedido_cliente_id = SD.pedido_cliente_id
		AND		SD.codigo_producto = '".$Codigo_Producto."'
		AND     SD.codigo_producto = IV.codigo_producto
		";
		}
		//print_r($sql);
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;

		$datos = array();
		if(!$rst->EOF)
		{
		$datos = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}
		$rst->Close();

		return $datos;
		}
	
    /**
    *
    *//*
    function ObtnerCantidadSolicitada($Codigo_Producto,$doc_tmp_id,$identificador)
		{
      if($identificador == 'FM')
      {
        $sql  = " SELECT SUM(solicitud) AS solicitud
                  FROM
                  (
                    SELECT  CASE WHEN FR.cantidad_pendiente IS NOT NULL THEN cantidad_pendiente
                                 ELSE SD.cantidad_solic END AS solicitud
                    FROM	  inventarios_productos IV,
                            (
                              SELECT  II.subclase_id,
                                      II.descripcion          
                              FROM    inventarios_productos IV,
                                      inv_subclases_inventarios II          
                              WHERE   IV.codigo_producto = '".$Codigo_Producto."'          
                              AND     IV.grupo_id = II.grupo_id          
                              AND     IV.clase_id = II.clase_id          
                              AND     IV.subclase_id = II.subclase_id        
                            ) SC,
                            inv_bodegas_movimiento_tmp_despachos_farmacias DF,
                            solicitud_productos_a_bodega_principal_detalle SD
                            LEFT JOIN inv_mov_pendientes_solicitudes_frm FR
                            ON 
                            (
                              SD.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id AND
                              SD.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id
                            )
                    WHERE   DF.doc_tmp_id = ".$doc_tmp_id."
                    AND     DF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id
                    AND     SD.codigo_producto = IV.codigo_producto
                    AND     IV.subclase_id = SC.subclase_id 
                  ) A ";
      }
      else
      {
        $sql = "SELECT  SUM(SD.numero_unidades) AS solicitud
                FROM	  inventarios_productos IV,
                        (
                          SELECT  II.subclase_id,
                                  II.descripcion          
                          FROM    inventarios_productos IV,
                                  inv_subclases_inventarios II          
                          WHERE   IV.codigo_producto = '".$Codigo_Producto."'           
                          AND     IV.grupo_id = II.grupo_id          
                          AND     IV.clase_id = II.clase_id          
                          AND     IV.subclase_id = II.subclase_id        
                        ) SC,
                        inv_bodegas_movimiento_tmp_despachos_clientes DF,
                        ventas_ordenes_pedidos_d SD
                WHERE   DF.doc_tmp_id = ".$doc_tmp_id."
                AND     DF.pedido_cliente_id = SD.pedido_cliente_id
                AND     SD.codigo_producto = IV.codigo_producto
                AND     IV.subclase_id = SC.subclase_id ";
      }
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
			$rst->Close();

			return $datos;
		}*/
    /**
    *
    */
	
	function ObtnerCantidadesIngresadasTodos($doc_tmp_id)
		{
			$sql .= "SELECT 
									round(SUM(A.cantidad))as cantidad,
									A.codigo_producto";
			$sql .= " FROM   ( ";
			$sql .= "         SELECT  
								round(SUM(TM.cantidad)) AS cantidad, ";
			$sql .= "        TM.codigo_producto ";
			$sql .= "         FROM	  	inv_bodegas_movimiento_tmp_d TM ";
			$sql .= "         WHERE  TM.doc_tmp_id = '".trim($doc_tmp_id)."' 
								AND TM.usuario_id = '".UserGetUID()."' ";
			$sql .= "         GROUP BY TM.codigo_producto ";
			$sql .= "		UNION ";
			$sql .= "		SELECT
								round(SUM(cantidad)) as cantidad,
								codigo_producto
								FROM
								inv_bodegas_movimiento_tmp_autorizaciones_despachos
								WHERE TRUE
								AND sw_autorizado = '0'
								AND doc_tmp_id = '".trim($doc_tmp_id)."'
								AND usuario_id = '".UserGetUID()."'
								GROUP BY codigo_producto ";
			$sql .= "       		) A	";
			$sql .= " group by A.codigo_producto ";

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
	
	
/*    function ObtnerCantidadesIngresadasTodos($doc_tmp_id)
		{
			$sql .= "SELECT * ";
			$sql .= "FROM   ( ";
			$sql .= "         SELECT  round(SUM(TM.cantidad)) AS cantidad, ";
			$sql .= "                 TM.codigo_producto, ";
			$sql .= "                 TM.observacion_cambio, ";
			$sql .= "                 EB.existencia ";
			$sql .= "         FROM	  	inv_bodegas_movimiento_tmp_d TM, ";
			$sql .= "                 existencias_bodegas EB ";
			$sql .= "         WHERE  TM.doc_tmp_id = ".$doc_tmp_id." ";
			$sql .= "         AND    EB.empresa_id = TM.empresa_id ";
			$sql .= "         AND    EB.centro_utilidad = TM.centro_utilidad ";
			$sql .= "         AND    EB.bodega = TM.bodega ";
			$sql .= "         AND    EB.codigo_producto = TM.codigo_producto ";
			$sql .= "         GROUP BY TM.codigo_producto,TM.observacion_cambio, EB.existencia ";
			$sql .= "       ) A	";
			$sql .= "WHERE  A.cantidad < A.existencia ";

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
		} */
		
		
		/**
    *
    */
    function ObtnerCantidadesIngresadasAutorizar($doc_tmp_id)
		{
			$sql = "				SELECT
										round(SUM(cantidad)) as cantidad,
										codigo_producto
										FROM
										inv_bodegas_movimiento_tmp_autorizaciones_despachos
										WHERE TRUE
										AND sw_autorizado = '0'
										AND doc_tmp_id = '".trim($doc_tmp_id)."'
										AND usuario_id = '".UserGetUID()."'
										GROUP BY codigo_producto;";

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
    *
    */
    function ObtenerCantidadesSolicitadasFarmacia($numero_pedido)
    {
      $sql  = "SELECT a.codigo_producto,";
      $sql .= "       FR.cantidad_pendiente AS cantidad_solic ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_detalle a,";
      $sql .= "       inv_mov_pendientes_solicitudes_frm FR ";
      $sql .= "WHERE  a.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    a.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id ";
      $sql .= "AND    a.solicitud_prod_a_bod_ppal_id = ".$numero_pedido." ";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
    
      $rst->Close();
      if(empty($datos))
      {
        $sql  = "SELECT a.codigo_producto, ";
        $sql .= "       a.cantidad_solic ";
        $sql .= "FROM   solicitud_productos_a_bodega_principal_detalle a ";
        $sql .= "WHERE  a.solicitud_prod_a_bod_ppal_id = ".$numero_pedido." ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;

        while(!$rst->EOF)
        {
          $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
      }
      return $datos;
    }
    /**
    *
    */
    function ObtenerCantidadesSolicitadasCliente($numero_pedido)
    {
      $sql  = "SELECT codigo_producto,";
      $sql .= "       numero_unidades AS cantidad_solic ";
      $sql .= "FROM   ventas_ordenes_pedidos_d ";
      $sql .= "WHERE  pedido_cliente_id = ".$numero_pedido." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
    
      $rst->Close();
      return $datos;
    }
    /**
    *
    */  
	
	/**
	*
	*/
	function ObtenerPendientesFarmacia($numero_pedido,$doc_tmp_id,$empresa_id,$centro_utilidad,$bodega)
	{
	$sql  = "		SELECT
					a.codigo_producto,
					fc_descripcion_producto(a.codigo_producto) as descripcion,
					(COALESCE(a.cantidad,0) - COALESCE(b.cantidad_temporal,0)) AS cantidad_pendiente,
					f.existencia
					FROM
					(SELECT   
					sd.codigo_producto,
					sd.cantidad_solic as cantidad
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.solicitud_prod_a_bod_ppal_id = '".trim($numero_pedido)."'
					and   s.sw_despacho = '0'
					UNION     
					SELECT 
					sd.codigo_producto,
					ips.cantidad_pendiente as cantidad
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s,
					inv_mov_pendientes_solicitudes_frm ips
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.solicitud_prod_a_bod_ppal_id = '".trim($numero_pedido)."'
					and   s.sw_despacho = '1'
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id) as a
					LEFT JOIN (SELECT
					codigo_producto,
					SUM(cantidad) as cantidad_temporal
					FROM
					inv_bodegas_movimiento_tmp_d
					WHERE
					doc_tmp_id = '".trim($doc_tmp_id)."'
					and usuario_id = '".UserGetUID()."'
					GROUP BY codigo_producto
					)as b ON (a.codigo_producto = b.codigo_producto)
					LEFT JOIN (
					SELECT
					codigo_producto,
					observacion
					FROM
					inv_bodegas_movimiento_tmp_justificaciones_pendientes
					WHERE
					doc_tmp_id = '".trim($doc_tmp_id)."'
					AND usuario_id = ".UserGetUID()."
					) as e ON (a.codigo_producto = e.codigo_producto)
					JOIN existencias_bodegas as f ON (a.codigo_producto = f.codigo_producto)
					WHERE
					COALESCE(a.cantidad,0) <> COALESCE(b.cantidad_temporal,0)
					AND e.observacion IS NULL
					AND f.empresa_id = '".trim($empresa_id)."'
					AND f.centro_utilidad = '".trim($centro_utilidad)."'
					AND f.bodega = '".trim($bodega)."'
					ORDER BY a.codigo_producto
					";
	/*print_r($sql);*/
	if(!$rst = $this->ConexionBaseDatos($sql))
	return false;

	$datos = array();
	while(!$rst->EOF)
	{
	$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
	$rst->MoveNext();
	}

	$rst->Close();
	return $datos;
	}
	/**
	*
	*/
	
	
	
    function ActualizarObservacion($doc_tmp_id,$codigo,$observacion)
		{
      $sql .= "UPDATE inv_bodegas_movimiento_tmp_d  ";
      $sql .= "SET    observacion_cambio = '".$observacion."' ";
      $sql .= "WHERE  doc_tmp_id = ".$doc_tmp_id." ";
      $sql .= "AND    codigo_producto = '".$codigo."' ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			 $rst->Close();
			return true;
		}
    /**
    *
    */
    function ObtenerObservacion($doc_tmp_id,$codigo)
		{
      $sql .= "SELECT observacion_cambio,  ";
      $sql .= "       codigo_producto ";
      $sql .= "FROM   inv_bodegas_movimiento_tmp_d  ";
      $sql .= "WHERE  doc_tmp_id = ".$doc_tmp_id." ";
      if($codigo)
      {
        $sql .= "AND    codigo_producto = '".$codigo."' ";
        $sql .= "AND    observacion_cambio IS NOT NULL ";
        $sql .= "AND    observacion_cambio <> '' ";
      }
      
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
		
	function IngresoJustificacionesDespacho($sql)
	{
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
    {
      $cad="Operacion Invalida";
      return false;//$cad;
    } 
    $resultado->Close();
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
      /*$dbconn->debug=true;*/
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