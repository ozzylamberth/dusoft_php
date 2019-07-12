<?php
  class CrearNotasFacturasDespachos extends ConexionBD
  {
    /**
    * Contructor
    */
    function CrearNotasFacturasDespachos(){}
		
    
	function ParametrosNotasDebitoCreditoFacturas($EmpresaId,$Parametro)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           documento_id_credito,
		       documento_id_debito
           
      FROM
         inv_notas_facturas_parametros
      WHERE
          id_parametros = '".$Parametro."'
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
	
	
	
	/**************************************************************************************
		* Busca los tipos de identificacion que puede tener un tercero
		* 
		* @return array 
		***************************************************************************************/
		function Listar_TiposIdTerceros()
		{
			$sql = "SELECT	
                      tipo_id_tercero,
                      descripcion
							FROM		tipo_id_terceros;";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    
    function Listar_TercerosProveedores($TipoIdTercero,$TerceroId,$Descripcion,$Empresa_Id,$offset)
		{
		//$this->debug=true;
      $sql = "
      SELECT DISTINCT
            ter.tipo_id_tercero,
            ter.tercero_id,
            ter.dv,
            ter.nombre_tercero,
            ter.direccion,
            ter.telefono,
            tp.pais
      FROM
          terceros ter,
          tipo_pais tp,
          inv_facturas_despacho factd
      WHERE
          ter.tipo_id_tercero ILIKE '%".$TipoIdTercero."%'
          AND
          ter.tercero_id ILIKE '%".$TerceroId."%'
          AND
          ter.nombre_tercero ILIKE '%".$Descripcion."%'
          AND
          ter.tipo_pais_id = tp.tipo_pais_id
          and
          ter.tipo_id_tercero = factd.tipo_id_tercero
          and
          ter.tercero_id = factd.tercero_id
          AND
          factd.empresa_id = '".$Empresa_Id."'
                   
      ";
			
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY ter.tipo_id_tercero ";
    //$sql .= " GROUP BY ter.tipo_id_tercero,ter.tipo_id_tercero ";
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
	
	//inv_facturas_despacho
  
  function Listar_FacturasProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$NumeroFactura,$offset)
		{
		//$this->debug=true;
      $sql = "
      SELECT
            *
      FROM
          inv_facturas_despacho fact
      WHERE
          fact.empresa_id = '".$Empresa_Id."'
          AND
          fact.factura ILIKE '%".$NumeroFactura."%'
          AND
          fact.tipo_id_tercero = '".$TipoIdTercero."' 
          and
          fact.tercero_id = '".$Tercero_Id."'
          
          ";
			
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;

        
      $sql .= " ORDER BY fact.fecha_registro ";
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
    
    
    function Detalle_FacturaProveedor($prefijo,$numero,$EmpresaId,$offset)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           copd.codigo_producto,
           --prod.descripcion,
           fc_descripcion_producto(copd.codigo_producto) as descripcion,
		   prod.contenido_unidad_venta,
           unid.descripcion as unidad,
           copd.cantidad as numero_unidades,
           copd.lote,
           copd.fecha_vencimiento,
           copd.valor_factura as valor
           
      FROM
         inv_facturas_despacho_d copd,
         inventarios_productos prod,
         unidades unid
      WHERE
          copd.inv_facturas_despacho = ".$numero."
          and
          copd.codigo_producto = prod.codigo_producto
          and
          prod.unidad_id = unid.unidad_id
          
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
    
    
    function Listar_Documentos($TipoDocGeneralId,$EmpresaId)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           documento_id,
           prefijo,
           numeracion,
           descripcion
      FROM
         documentos
      WHERE
          empresa_id='".$EmpresaId."'
          and
          tipo_doc_general_id='".$TipoDocGeneralId."'
          and
          sw_estado = '1'
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
    
    function BuscarTercero($TipoIdTercero,$TerceroId)
		{
	//$this->debug=true;
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
		//$this->debug=true;
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
    
    
     function BuscarFactura($prefijo,$numero,$EmpresaId)
		{
		//$this->debug=true;
      $sql = "
      SELECT
           *
      FROM
         inv_facturas_despacho
      WHERE
          prefijo = '".$prefijo."'
          and
          numero = '".$numero."'
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
	/*
	* Obtener el id de Temporal
	*/
	function DocTemporalId()
		{
		//$this->debug=true;
      $sql = "
			select 
			max(doc_nota_tmp_id) as doc_nota_tmp_id
			FROM 
			inv_notas_facturas_despacho_tmp
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
		
	function CrearDocumentoTemporalNota($EmpresaId,$tercero_id,$tipo_id_tercero,$prefijo,$numero,$documento_id,$doc_nota_tmp_id)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_notas_facturas_despacho_tmp (";
    $sql .= "       doc_nota_tmp_id     , ";
	$sql .= "       documento_id     , ";
	$sql .= "       empresa_id     , ";
	$sql .= "       prefijo     , ";
	$sql .= "       numero     , ";
    $sql .= "       fecha_registro     , ";
    $sql .= "       tercero_id     , ";
    $sql .= "		tipo_id_tercero ,";
	$sql .= "       usuario_id      ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$doc_nota_tmp_id."',";
	$sql .= "        '".$documento_id."',";
	$sql .= "        '".$EmpresaId."',";
    $sql .= "        '".$prefijo."',";
    $sql .= "        ".$numero.",";
    $sql .= "        NOW(),";
    $sql .= "        '".$tercero_id."',";
	$sql .= "        '".$tipo_id_tercero."',";
	$sql .= "        '".UserGetUID()."'";
	$sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	
	
	
	
	 /**********************************************************************************
		* Insertar una Nota
		* 
		* @return token
		************************************************************************************/
		
	function CrearNota($TipoIdTercero,$TerceroId,$ValorNota,$NumeroFactura,$DocumentoId,$Prefijo,$Numeracion,$EmpresaId)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_notas_facturas_despacho (";
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
		function Detalle_FacturaProveedor2($prefijo,$numero,$Offset)
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
         inventarios_productos prod,
          unidades unid,
		     inv_facturas_despacho_d fact
      WHERE
      
      fact.prefijo = '".$prefijo."'
      and
      fact.numero = '".$numero."'
		  and
		  fact.empresa_id = '".$EmpresaId."'
		  and
		  fact.orden_pedido_id = copd.orden_pedido_id
          and
          copd.codigo_producto = prod.codigo_producto
          and
          prod.unidad_id = unid.unidad_id
          and
          copd.numero_unidades_recibidas > 0
          
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
		
	function InsertarDetalleNota($EmpresaId,$doc_nota_tmp_id,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote)
	{
  
   if($Codigo_Producto!="")
      {
      $campo = " ,codigo_producto ";
      $valor = "    ,'".$Codigo_Producto."'    ";
      }

        //$this->debug=true;
        $sql  = "INSERT INTO inv_notas_facturas_despacho_d_tmp (";
        //$sql .= "		nota_factura_proveedor_d_id,";
        $sql .= "       empresa_id     , ";
        $sql .= "       doc_nota_tmp_id     , ";
        $sql .= "		concepto ,";
        $sql .= "       valor_concepto     , ";
        $sql .= "       lote      ";
        $sql .= "       ".$campo."    ) ";
        $sql .= "VALUES ( ";
        //$sql .= "        DEFAULT,";
        $sql .= "        '".$EmpresaId."',";
        $sql .= "        '".$doc_nota_tmp_id."',";
        $sql .= "        '".$Concepto."',";
        $sql .= "        '".$ValorConcepto."',";
        $sql .= "        '".$Lote."' ";
        $sql .= "        ".$valor." ";
        $sql .= "       ); ";			

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
		
	function ObtenerDocumentoTemporal($Empresa_Id,$doc_nota_tmp_id)
		{
	  
	 //$this->debug=true;
      $sql = "
      SELECT
           *
      FROM
         inv_notas_facturas_despacho_tmp
      WHERE
          empresa_id='".$Empresa_Id."'
          and
          doc_nota_tmp_id ='".$doc_nota_tmp_id."'
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
		
		
	function NotaDetalles($Empresa_Id,$doc_nota_tmp_id)
		{
	  
	  //$this->debug=true;
      $sql = "
      SELECT
           concepto,
		   doc_nota_tmp_id,
		   valor_concepto,
		   item_id as detalle,
		   codigo_producto,
		   lote
      FROM
         inv_notas_facturas_despacho_d_tmp
      WHERE
          empresa_id='".$Empresa_Id."'
          and
          doc_nota_tmp_id ='".$doc_nota_tmp_id."'
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
		
		
		
		
		function CrearDocumento($CabeceraDocTemporal,$DocumentoId,$Total)
	{
	//print_r($CabeceraDocTemporal);
	//print_r($DocumentoId);
	//this->debug=true;
	$sql  = "INSERT INTO inv_notas_facturas_despacho (";
  $sql .= "       documento_id     , ";
	$sql .= "       empresa_id     , ";
  $sql .= "		    prefijo_factura ,";
  $sql .= "		    numero_factura ,";
	$sql .= "       fecha_registro     , ";
	$sql .= "       numero     , ";
	$sql .= "       prefijo     , ";
	$sql .= "       tercero_id     , ";
	$sql .= "       tipo_id_tercero     , ";
	$sql .= "       usuario_id     , ";
	$sql .= "       valor_nota      ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
	$sql .= "        '".$CabeceraDocTemporal[0]['documento_id']."',";
	$sql .= "        '".$CabeceraDocTemporal[0]['empresa_id']."',";
    $sql .= "        '".$CabeceraDocTemporal[0]['prefijo']."',";
    $sql .= "        '".$CabeceraDocTemporal[0]['numero']."',";
    $sql .= "        NOW(),";
	$sql .= "        '".$DocumentoId[0]['numeracion']."',";
	$sql .= "        '".$DocumentoId[0]['prefijo']."',";
	$sql .= "        '".$CabeceraDocTemporal[0]['tercero_id']."',";
	$sql .= "        '".$CabeceraDocTemporal[0]['tipo_id_tercero']."',";
	$sql .= "        '".UserGetUID()."',";
	$sql .= "        '".$Total."'";
	$sql .= "       ); ";			
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
		
		/**********************************************************************************
		* Insertar una Nota
		* 
		* @return token
		************************************************************************************/
		
	function InsertarDocumentoDetalle($EmpresaId,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote,$Prefijo,$Numero)
	{
	  if($Codigo_Producto!="")
      {
      $campo = " ,codigo_producto ";
      $valor = "    ,'".$Codigo_Producto."'   ";
      }
      //$this->debug=true;
      $sql  = "INSERT INTO inv_notas_facturas_despacho_d (";
      $sql .= "       item_id      ";
      $sql .= "       ".$campo." ";
      $sql .= "       ,concepto     , ";
      $sql .= "		    empresa_id ,";
      $sql .= "		    lote ,";
      $sql .= "       numero     , ";
      $sql .= "       prefijo     , ";
      $sql .= "       valor_concepto  ";
      $sql .= "        ) ";
      $sql .= "VALUES ( ";
      $sql .= "        DEFAULT ";
      $sql .= "        ".$valor." ";
      $sql .= "        ,'".$Concepto."',";
      $sql .= "        '".$EmpresaId."',";
      $sql .= "        '".$Lote."',";
      $sql .= "        '".$Numero."',";
      $sql .= "        '".$Prefijo."',";
      $sql .= "        '".$ValorConcepto."'";
      $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
	}	
	
		
  	function ActualizarDocumento($EmpresaId,$DocumentoId,$Numeracion)
	{
	$num_nuevo=$Numeracion+1;
	  $sql  = "UPDATE documentos ";
    $sql .= "SET ";
    $sql .= "numeracion = ".$num_nuevo;
    $sql .= " Where ";
    $sql .= "empresa_id ='".$EmpresaId."' ";
	$sql .= " and ";
	$sql .= "documento_id ='".$DocumentoId."' ";
	//$this->debug=true;
//print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  function NotasFacturaProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$prefijo,$numero)
		{
		//$this->debug=true;
      $sql = "
      SELECT
          doc.descripcion,
		  infp.valor_nota,
		  infp.prefijo,
		  infp.numero
		  
		  
      FROM
         inv_notas_facturas_despacho infp,
		 documentos doc
      WHERE
          infp.prefijo_factura = '".$prefijo."'
          and
          infp.numero_factura = ".$numero."
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
         inv_notas_facturas_despacho_d
      WHERE
          empresa_id = '".$Empresa_Id."'
		  and
		  prefijo = '".$Prefijo."'
		  and
		  numero = '".$Numeracion."'
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
		
		
		
  function AnularNota($Formulario)
	{
	$sql  = "UPDATE inv_notas_facturas_despacho ";
    $sql .= "SET ";
    $sql .= " observacion_anulacion = '".$Formulario['justificacion']."',";
    $sql .= " usuario_id_anulador = ".UserGetUID().",";
    $sql .= " sw_anulado = '1',";
    $sql .= " fecha_anulacion = NOW() ";
    $sql .= " Where ";
    $sql .= " empresa_id ='".$Formulario['empresa_id']."' ";
	$sql .= " and ";
	$sql .= " prefijo ='".$Formulario['prefijo']."' ";
	$sql .= " and ";
	$sql .= " numero =".$Formulario['numeracion']." ";
	//$this->debug=true;
//print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  }
?>