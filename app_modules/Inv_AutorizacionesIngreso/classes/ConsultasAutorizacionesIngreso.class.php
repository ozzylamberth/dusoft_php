<?php
  class ConsultasAutorizacionesIngreso extends ConexionBD
  {
    /**
    * Contructor
    */
    function ConsultasAutorizacionesIngreso(){}
		
    
    function Listar_ProductosPorAutorizar($Nombre,$EmpresaId,$OrdenCompra,$offset)
		{
		//$this->debug=true;
    if($OrdenCompra!="")
      $filtro = "and copfoc.orden_pedido_id = ".$OrdenCompra." ";
    
      $sql = "
      SELECT
      copfoc.codigo_producto,
			copfoc.doc_tmp_id,
			copfoc.empresa_id,
			copfoc.centro_utilidad,
			copfoc.bodega,
			copfoc.orden_pedido_id,
			copfoc.usuario_id,
			copfoc.justificacion_ingreso,
			copfoc.fecha_ingreso,
			copfoc.cantidad,
			copfoc.lote,
			copfoc.fecha_vencimiento,
			copfoc.porcentaje_gravamen,
			copfoc.total_costo,
			copfoc.local_prod,
			copfoc.sw_autorizado,
			copfoc.item_id,
			copfoc.valor_unitario_compra,
			copfoc.valor_unitario_factura,
			fc_descripcion_producto(copfoc.codigo_producto) as producto, 
			usu.nombre
			
      FROM
      inventarios_productos prod,
		  system_usuarios usu,
		  compras_ordenes_pedidos_productosfoc copfoc
          
      WHERE
      copfoc.empresa_id = '".$EmpresaId."'
		  and
		  copfoc.codigo_producto = prod.codigo_producto
		  and
		  prod.descripcion ILIKE '%".$Nombre."%'
		  and
		  copfoc.usuario_id = usu.usuario_id
		  and
		  copfoc.sw_autorizado = '0'
		  ";
			$sql .= $filtro;
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY copfoc.fecha_ingreso ";
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
	
	
	function ObtenerItemId()
		{
		//$this->debug=true;
		
		$sql = "SELECT nextval('inv_bodegas_movimiento_tmp_d_item_id_seq'::regclass)as item;";
	
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
	
	
	//inv_facturas_proveedores
    function AgregarItemADocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id,$fecha_venc,$lotec,$localizacion,$UsuarioAutorizador,$OrdenPedidoId,$EmpresaId,$CentroUtilidad,$Bodega,$item_id,$item_id_compras)
		{
		//$this->debug=true;
		
		$sql = "INSERT INTO inv_bodegas_movimiento_tmp_d
                (
                    item_id,
                    usuario_id,
                    doc_tmp_id,
                    empresa_id,
                    centro_utilidad,
                    bodega,
                    codigo_producto,
                    cantidad,
                    porcentaje_gravamen,
                    total_costo,
                    fecha_vencimiento,
                    lote,
                    local_prod,
                    item_id_compras
                )
                VALUES
                (
                    $item_id,
                    $usuario_id,
                    $doc_tmp_id,
                    '".$EmpresaId."',
                    '".$CentroUtilidad."',
                    '".$Bodega."',
                    '".$codigo_producto."',
                     $cantidad,
                     $porcentaje_gravamen,
                     $total_costo,
                    '$fecha_venc',
                    '$lotec',
                    '$localizacion',
                     $item_id_compras
                )";
	
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
    
    
    function ConsultarUsuario($UsuarioAutorizadorId)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           nombre,
		   descripcion
      FROM
            system_usuarios
      WHERE
          usuario_id = ".$UsuarioAutorizadorId."
             ";
			
         			
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
    
    
    function ConsultarPrimerAutorizador($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id)
		{
	//	$this->debug=true;
      $sql = "
      SELECT
		   usuario_id_autorizador,
		   usuario_id_autorizador_2,
		   observacion_autorizacion
      FROM
         compras_ordenes_pedidos_productosfoc
      WHERE
          empresa_id = '".$EmpresaId."'
		  and
		  centro_utilidad = '".$CentroUtilidad."'
		  and
		  bodega = '".$Bodega."'
		  and
		  orden_pedido_id = '".$OrdenPedidoId."'
		  and
		  codigo_producto = '".$codigo_producto."'
		  and
		  lote = '".$lotec."'
		  and
		  doc_tmp_id = '".$doc_tmp_id."'
          ";
			
			
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
    
	
	function Autorizar($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id,$Param,$Justificacion_Autorizacion)
	{
	 
	$sql  = "UPDATE compras_ordenes_pedidos_productosfoc ";
    $sql .= "SET ";
    $sql .= $Param.",";
	$sql .= "observacion_autorizacion = '".$Justificacion_Autorizacion."'";
    $sql .= "
				WHERE
				empresa_id = '".$EmpresaId."'
				and
				  centro_utilidad = '".$CentroUtilidad."'
				  and
				  bodega = '".$Bodega."'
				  and
				  orden_pedido_id = '".$OrdenPedidoId."'
				  and
				  codigo_producto = '".$codigo_producto."'
				  and
				  lote = '".$lotec."'
				  and
				  doc_tmp_id = '".$doc_tmp_id."'
	
			";
	//$this->debug=true;
  //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	

 /*
    *  Funcion para el switch de ingreso de productos a la tabla temporal
    */
  function ItemAgregadoCompras($codigo,$can,$lote,$fecha_vencimiento,$ItemId)
    {
     //$this->debug=true;  
	$sql  = " UPDATE compras_ordenes_pedidos_detalle ";
  $sql .= " SET ";
  $sql .= " fecha_vencimiento_temp = '".$fecha_vencimiento."', ";
  $sql .= " lote_temp = '".$lote."' ";
  $sql .= " Where ";
  $sql .= " item_id = ".$ItemId." ";
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
	
	
	function CambioEstadoAutorizacion($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id)
	{
	 
	$sql  = "UPDATE compras_ordenes_pedidos_productosfoc ";
    $sql .= "SET ";
    $sql .= "sw_autorizado = '1'";
	$sql .= "
				WHERE
				empresa_id = '".$EmpresaId."'
				and
				  centro_utilidad = '".$CentroUtilidad."'
				  and
				  bodega = '".$Bodega."'
				  and
				  orden_pedido_id = '".$OrdenPedidoId."'
				  and
				  codigo_producto = '".$codigo_producto."'
				  and
				  lote = '".$lotec."'
				  and
				  doc_tmp_id = '".$doc_tmp_id."'
	
			";
	//$this->debug=true;
//print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	
    function BuscarTercero($TipoIdTercero,$TerceroId)
		{
	//	$this->debug=true;
      $sql = "
      SELECT
           ter.tipo_id_tercero,
           ter.tercero_id,
           ter.nombre_tercero,
           ter.direccion,
           ter.telefono,
           tpa.pais,
           ter.dv
           
      FROM
         terceros ter,
         tipo_pais tpa
      WHERE
          ter.tipo_id_tercero ='".$TipoIdTercero."'
          and
          tercero_id='".$TerceroId."'
          and
          ter.tipo_pais_id = tpa.tipo_pais_id
          ";
			
			
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
    
    
    function BuscarDocumento($DocumentoId)
		{
	//	$this->debug=true;
      $sql = "
      SELECT
           descripcion,
           numeracion,
           prefijo
           
      FROM
         documentos
      WHERE
          documento_id = ".$DocumentoId."
          ";
			
			
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
    
    
     function BuscarFactura($numero_factura,$EmpresaId)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           numero_factura,
           empresa_id,
           codigo_proveedor_id,
           fecha,
           observaciones,
           valor_factura,
           orden_pedido_id
           
      FROM
         inv_facturas_proveedores
      WHERE
          numero_factura = '".$numero_factura."'
          and
          empresa_id = '".$EmpresaId."'
          ";
			
			
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
	
	
	 /**********************************************************************************
		* Insertar una Nota
		* 
		* @return token
		************************************************************************************/
		
	function CrearNota($TipoIdTercero,$TerceroId,$ValorNota,$NumeroFactura,$DocumentoId,$Prefijo,$Numeracion,$EmpresaId)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_notas_facturas_proveedor (";
    $sql .= "       documento_id     , ";
	$sql .= "       empresa_id     , ";
	$sql .= "       fecha_registro     , ";
	$sql .= "       numeracion     , ";
    $sql .= "       numero_factura     , ";
    $sql .= "       prefijo     , ";
    $sql .= "		sw_anulado ,";
	$sql .= "       tercero_id     , ";
    $sql .= "       tipo_id_tercero     , ";
	$sql .= "       usuario_id     , ";
	$sql .= "       valor_nota     ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$DocumentoId."',";
	$sql .= "        '".$EmpresaId."',";
    $sql .= "        NOW(),";
    $sql .= "        '".$Numeracion."',";
    $sql .= "        '".$NumeroFactura."',";
	$sql .= "        '".$Prefijo."',";
	$sql .= "        '1',";//queda en estado 1, hasta que el documento sea creado.
	$sql .= "        '".$TerceroId."',";
    $sql .= "        '".$TipoIdTercero."',";
	$sql .= "        '".UserGetUID()."',";
	$sql .= "        '".$ValorNota."'";
	$sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		/*
		* Funcion en Caso de que sea seleccionado un producto Asociado a un concepto de nota
		*/
		function Detalle_FacturaProveedor2($NumeroFactura,$EmpresaId,$Offset)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           
		   copd.codigo_producto,
           prod.descripcion,
           prod.contenido_unidad_venta,
           unid.descripcion as unidad,
           copd.numero_unidades,
           copd.lote_temp,
           copd.fecha_vencimiento_temp,
           copd.valor
           
      FROM
         compras_ordenes_pedidos_detalle copd,
         inventarios_productos prod,
         unidades unid,
		 inv_facturas_proveedores fact
      WHERE
          fact.numero_factura = '".$NumeroFactura."'
		  and
		  fact.empresa_id = '".$EmpresaId."'
		  and
		  fact.orden_pedido_id = copd.orden_pedido_id
          and
          copd.codigo_producto = prod.codigo_producto
          and
          prod.unidad_id = unid.unidad_id
          and
          copd.lote_temp <> ''
          
          ";
			
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;

        
      $sql .= " ORDER BY copd.codigo_producto ";
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
		
		/**********************************************************************************
		* Insertar una Nota
		* 
		* @return token
		************************************************************************************/
		
	function InsertarDetalleNota($EmpresaId,$Prefijo,$Numeracion,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote)
	{
	  
   // $this->debug=true;
    $sql  = "INSERT INTO inv_notas_facturas_proveedor_d (";
    //$sql .= "		nota_factura_proveedor_d_id,";
	$sql .= "       empresa_id     , ";
	$sql .= "       numeracion     , ";
    $sql .= "       prefijo     , ";
    $sql .= "		concepto ,";
	$sql .= "       valor_concepto     , ";
	$sql .= "       lote     , ";
    $sql .= "       codigo_producto     ) ";
    $sql .= "VALUES ( ";
	//$sql .= "        DEFAULT,";
    $sql .= "        '".$EmpresaId."',";
	$sql .= "        '".$Numeracion."',";
    $sql .= "        '".$Prefijo."',";
    $sql .= "        '".$Concepto."',";
    $sql .= "        '".$ValorConcepto."',";
	$sql .= "        '".$Lote."',";
	$sql .= "        '".$Codigo_Producto."'";
	$sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
		
	function NotaDetalles($Empresa_Id,$Prefijo,$Numeracion,$offset)
		{
	  
	  //$this->debug=true;
      $sql = "
      SELECT
           concepto,
		   valor_concepto,
		   nota_factura_proveedor_d_id as detalle,
		   codigo_producto,
		   lote
      FROM
         inv_notas_facturas_proveedor_d
      WHERE
          empresa_id='".$Empresa_Id."'
          and
          prefijo ='".$Prefijo."'
          and
		  numeracion ='".$Numeracion."'
          ";
			
			
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
		
		function CrearDocumento($EmpresaId,$Prefijo,$Numeracion,$Valor_Nota)
	{
	  $sql  = "UPDATE inv_notas_facturas_proveedor ";
    $sql .= "SET ";
    $sql .= "valor_nota = '".$Valor_Nota."',";
    $sql .= "sw_anulado = '0'";
	$sql .= " Where ";
    $sql .= "empresa_id ='".$EmpresaId."' ";
	$sql .= " and ";
	$sql .= "prefijo ='".$Prefijo."' ";
	$sql .= " and ";
	$sql .= "numeracion ='".$Numeracion."' ";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
  	function ActualizarDocumento($EmpresaId,$Prefijo,$Numeracion)
	{
	$num_nuevo=$Numeracion+1;
	  $sql  = "UPDATE documentos ";
    $sql .= "SET ";
    $sql .= "numeracion = ".$num_nuevo;
    $sql .= " Where ";
    $sql .= "empresa_id ='".$EmpresaId."' ";
	$sql .= " and ";
	$sql .= "prefijo ='".$Prefijo."' ";
	$sql .= " and ";
	$sql .= "numeracion ='".$Numeracion."' ";
	$this->debug=true;
//print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  function NotasFacturaProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$Numero_factura)
		{
		//$this->debug=true;
      $sql = "
      SELECT
          doc.descripcion,
		  infp.valor_nota,
		  infp.prefijo,
		  infp.numeracion
		  
		  
      FROM
         inv_notas_facturas_proveedor infp,
		 documentos doc
      WHERE
          infp.numero_factura = '".$Numero_factura."'
          and
          infp.empresa_id = '".$Empresa_Id."'
		  and
		  infp.tercero_id = '".$Tercero_Id."'
		  and
		  infp.tipo_id_tercero = '".$TipoIdTercero."'
		  and
		  infp.documento_id = doc.documento_id
		  and
		  infp.sw_anulado = '0'
          ";
			
			
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
		
		function DetallesNotaFacturaProveedor($Empresa_Id,$Prefijo,$Numeracion)
		{
		//$this->debug=true;
      $sql = "
      SELECT
          concepto,
		  valor_concepto,
		  codigo_producto,
		  lote
		  
      FROM
         inv_notas_facturas_proveedor_d
      WHERE
          empresa_id = '".$Empresa_Id."'
		  and
		  prefijo = '".$Prefijo."'
		  and
		  numeracion = '".$Numeracion."'
          ";
			
			
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
  }
?>