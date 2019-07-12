<?php
  class CrearNotasFacturasProveedores extends ConexionBD
  {
    /**
    * Contructor
    */
    function CrearNotasFacturasProveedores(){}
		
    
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
							FROM	tipo_id_terceros;";
						
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
    
    
        function Obtener_FacturasProveedor($filtros,$empresa_id,$offset)
		{
		 //$this->debug=true;
         //print_r($filtros);
         if($filtros['tipo_id_tercero']!="")
           $filtro .= " and c.tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";
         if($filtros['tercero_id']!="")
           $filtro .= " and c.tercero_id = '".$filtros['tercero_id']."' ";
         if($filtros['nombre_tercero']!="")
           $filtro .= " and c.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
         if($filtros['numero_factura']!="")
           $filtro .= " and a.numero_factura ILIKE '%".$filtros['numero_factura']."%' ";
   
    
         $sql = "
              select
              c.tipo_id_tercero,
              c.tercero_id,
              c.nombre_tercero,
              b.codigo_proveedor_id,
              a.numero_factura,
              a.observaciones,
              TO_CHAR(a.fecha_registro,'DD-MM-YYY') as fecha_registro,
              a.valor_factura,
              a.saldo
              from
              inv_facturas_proveedores as a
              JOIN terceros_proveedores as b ON (a.codigo_proveedor_id = b.codigo_proveedor_id)
              JOIN terceros as c ON (b.tipo_id_tercero = c.tipo_id_tercero) 
              and (b.tercero_id = c.tercero_id)
              Where
              a.saldo >0
              and a.empresa_id = '".$empresa_id."'
              ".$filtro."
            ";
			
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
               return false;
            
			$sql .= " ORDER BY c.nombre_tercero ";
            $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
			
		    if(!$resultado = $this->ConexionBaseDatos($sql))
              return false;
        
            $documentos=Array();
            while(!$resultado->EOF)
            {
             $documentos[ ] = $resultado->GetRowAssoc($ToUpper = false);
             $resultado->MoveNext();
            }
    
            $resultado->Close();
            // return $sql;
            return $documentos;
		}
	
	//inv_facturas_proveedores
  
  function BuscarTemporales($Filtros)
		{
      //print_r($Filtros);
     /*$this->debug=true;*/
      $sql = "
			select 
			a.factura_proveedor, 
			a.codigo_proveedor_id, 
			a.empresa_id, 
			a.fecha_registro, 
			a.usuario_id, 
			c.tipo_id_tercero, 
			c.tercero_id, 
			c.nombre_tercero, 
			d.usuario, 
			e.saldo, 
			e.valor_factura, 
			e.valor_descuento,
			e.porc_rtf,
			e.porc_ica,
			e.porc_rtiva,
			f.subtotal,
			f.iva_total,
			f.total,
			TO_CHAR(e.fecha_registro,'YYYY') as anio_factura
			from 
			inv_notas_facturas_proveedor_tmp as a 
			JOIN terceros_proveedores as b ON(a.codigo_proveedor_id = b.codigo_proveedor_id) 
			JOIN terceros as c ON (b.tipo_id_tercero = c.tipo_id_tercero) 
			and (b.tercero_id = c.tercero_id) 
			JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id) 
			JOIN inv_facturas_proveedores as e ON(a.factura_proveedor = e.numero_factura) 
			and (a.codigo_proveedor_id = e.codigo_proveedor_id)
			JOIN (
			SELECT
			x.codigo_proveedor_id,
			x.numero_factura,
			SUM(((x.valor/((x.porc_iva/100)+1))*x.cantidad)) as subtotal,
			SUM(((x.valor-(x.valor/((x.porc_iva/100)+1)))*x.cantidad)) as iva_total,
			SUM((x.valor * x.cantidad)) as total
			FROM
			inv_facturas_proveedores_d as x
			WHERE
			x.numero_factura = '".$Filtros['numero_factura']."'
			and x.codigo_proveedor_id = ".$Filtros['codigo_proveedor_id']."
			group by
			x.codigo_proveedor_id,
			x.numero_factura
			) as f ON (e.numero_factura = f.numero_factura)
			AND (e.codigo_proveedor_id = f.codigo_proveedor_id)
            where
            a.factura_proveedor = '".$Filtros['numero_factura']."'
            and a.codigo_proveedor_id = ".$Filtros['codigo_proveedor_id']."; ";
						
		 if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
		}
    
    function BuscarDetalle($Filtros,$buscador,$offset)
		{
      //print_r($Filtros);
      //$this->debug=true;
      //print_r($buscador);
      if($buscador['codigo_producto']!="")
      {
      $adicional .= " and c.codigo_producto = '".$buscador['codigo_producto']."' ";
      }
      
      if($buscador['descripcion']!="")
      {
      $adicional .= " and c.descripcion ILIKE '%".$buscador['descripcion']."%' ";
      }
      
      $sql = "
            select 
            a.codigo_producto, 
            sum(a.cantidad)as cantidad, 
            sum(a.cantidad_devuelta)as cantidad_devuelta, 
            AVG(a.valor)as valor, 
            AVG(a.porc_iva)as porc_iva, 
            a.codigo_proveedor_id, 
            a.numero_factura, 
            fc_descripcion_producto(a.codigo_producto) as descripcion,
            CASE WHEN (b.codigo_producto IS NOT NULL)
            THEN 'disabled checked' ELSE ' ' END as checkbox 
            from inv_facturas_proveedores_d as a 
            LEFT JOIN inv_notas_facturas_proveedor_d_tmp as b ON (a.numero_factura = b.numero_factura)
            and (a.codigo_producto = b.codigo_producto) and (a.codigo_proveedor_id = b.codigo_proveedor_id)
            JOIN inventarios_productos as c ON (a.codigo_producto = c.codigo_producto)
            where 
            a.numero_factura = '".$Filtros['numero_factura']."'
            and a.codigo_proveedor_id = ".$Filtros['codigo_proveedor_id']."
            
            ".$adicional."
            
            group by a.codigo_producto, 
            a.codigo_proveedor_id, 
            a.numero_factura,
            b.codigo_producto ";
            
      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      $this->ProcesarSqlConteo($cont,$offset);
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
						
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
    
    
    	function Crear_Temporal($Datos)
	{
	  
      //$this->debug=true;
      $sql  = "INSERT INTO inv_notas_facturas_proveedor_tmp (";
      $sql .= "       codigo_proveedor_id     , ";
      $sql .= "       factura_proveedor     , ";
      $sql .= "       empresa_id     , ";
      $sql .= "       usuario_id      ";
      $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$Datos['codigo_proveedor_id'].",";
      $sql .= "        '".$Datos['numero_factura']."',";
      $sql .= "        '".$Datos['datos']['empresa_id']."',";
      $sql .= "        ".UserGetUID()."";
      $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
    function Detalle_NotaTemporal($Datos,$tipo_nota)
		{
        
        if($tipo_nota!="")
        {
        $filtro = " and a.nota_mayor_valor = '".$tipo_nota."' ";
        }
       /* $this->debug=true;*/
        $sql = "
            select
            a.codigo_producto,
            fc_descripcion_producto(a.codigo_producto) as descripcion,
            a.numero_factura,
            a.codigo_proveedor_id,
            a.valor_concepto,
            a.cantidad,
            a.valor,
			a.porc_iva,
			((a.porc_iva/100)*a.valor_concepto) as iva, 
            a.observacion,
            a.nota_mayor_valor,
            a.concepto,
            a.concepto_especifico,
            c.descripcion_concepto_general,
            d.descripcion_concepto_especifico,
            e.usuario,
            CASE WHEN (a.nota_mayor_valor ='1')
            THEN 'NOTA POR MAYOR VALOR' ELSE 'NOTA POR MENOR VALOR' END as tipo_nota,
			a.sube_baja_costo,
			CASE WHEN (a.sube_baja_costo ='1') AND (a.nota_mayor_valor ='1')
			THEN 'APLICA NOTA BAJA COSTO' 
			WHEN (a.sube_baja_costo ='1') AND (a.nota_mayor_valor ='0')
			THEN 'APLICA NOTA SUBE COSTO' 
			ELSE 'NO APLICA NOTA SUBE/BAJA COSTO' END as operacion	
            from
            inv_notas_facturas_proveedor_d_tmp as a
            JOIN glosas_concepto_general_especifico as b ON (a.concepto_especifico = b.codigo_concepto_especifico)
            and (a.concepto = b.codigo_concepto_general)
            JOIN glosas_concepto_general as c ON (b.codigo_concepto_general = c.codigo_concepto_general)
            JOIN glosas_concepto_especifico as d ON (b.codigo_concepto_especifico = d.codigo_concepto_especifico)
            JOIN system_usuarios as e ON (a.usuario_id = e.usuario_id)
            WHERE
            a.numero_factura = '".$Datos['numero_factura']."'
            and a.codigo_proveedor_id = ".$Datos['codigo_proveedor_id']."
            ".$filtro."
        ";
		
      $sql .= " ORDER BY a.codigo_producto ";
     	
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
    
    
    function Parametros_Notas($Datos,$modulo)
		{
        //$this->debug=true;
        $sql = "
        select
        a.empresa_id,
        a.documento_id_credito,
        a.documento_id_debito,
        b.prefijo as prefijo_credito,
        b.numeracion as numeracion_credito,
        b.descripcion as descripcion_credito,
        c.prefijo as prefijo_debito,
        c.numeracion as numeracion_debito,
        c.descripcion as descripcion_debito
        from
        inv_notas_facturas_parametros as a
        JOIN documentos as b ON (a.documento_id_credito = b.documento_id)
        and(a.empresa_id = b.empresa_id)
        JOIN documentos as c ON (a.documento_id_debito = c.documento_id)
        and(a.empresa_id = c.empresa_id)
        where
        a.empresa_id='".$Datos['datos']['empresa_id']."'
        and a.modulo = '".$modulo."'
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
    
    	function GuardarTemporal_Detalle($codigo_producto,$cantidad,$codigo_concepto_general,$codigo_concepto_especifico,
          $valor_concepto,$observacion,$nota_mayor_valor,$valor,$Datos,$porc_iva,$sube_baja_costo)
	{
      if($nota_mayor_valor=="")
      $nota_mayor_valor= '0';
	  
	  if($sube_baja_costo=="")
      $sube_baja_costo= '0';
	    /*$this->debug=true;*/
      //print_r($Datos);
      $sql  = "INSERT INTO inv_notas_facturas_proveedor_d_tmp (";
      $sql .= "       codigo_proveedor_id     , ";
      $sql .= "       numero_factura     , ";
      $sql .= "       empresa_id     , ";
      $sql .= "       usuario_id     ,";
      $sql .= "       codigo_producto     ,";
      $sql .= "       cantidad     ,";
      $sql .= "       concepto     ,";
      $sql .= "       concepto_especifico     ,";
      $sql .= "       valor_concepto     ,";
      $sql .= "       observacion     ,";
      $sql .= "       nota_mayor_valor,     ";
      $sql .= "       valor,     ";
      $sql .= "       porc_iva,     ";
      $sql .= "       sube_baja_costo     ";
      $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$Datos['codigo_proveedor_id'].",";
      $sql .= "        '".$Datos['numero_factura']."',";
      $sql .= "        '".$Datos['datos']['empresa_id']."',";
      $sql .= "        ".UserGetUID().",";
      $sql .= "        '".$codigo_producto."',";
      $sql .= "        ".$cantidad.",";
      $sql .= "        '".$codigo_concepto_general."',";
      $sql .= "        '".$codigo_concepto_especifico."',";
      $sql .= "        ".$valor_concepto.",";
      $sql .= "        '".$observacion."',";
      $sql .= "        '".$nota_mayor_valor."',";
      $sql .= "        ".$valor.", ";
      $sql .= "        ".$porc_iva.", ";
      $sql .= "        '".$sube_baja_costo."' ";
      $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
  	function EliminarItem_Temporal($Datos)
	{
      $sql  = "DELETE FROM inv_notas_facturas_proveedor_d_tmp ";
      $sql .= " Where ";
      $sql .= "     codigo_producto ='".$Datos['codigo_producto']."' ";
      $sql .= " and codigo_proveedor_id = ".$Datos['codigo_proveedor_id']." ";
      $sql .= " and numero_factura ='".$Datos['numero_factura']."' ";
      //$this->debug=true;
      //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
    function GuardarTransaccion($documento_id,$prefijo,$numeracion,$Datos,$DetalleTemporal,$valor_nota,$tabla,$campo,$signo,$op)
		{
		/*$this->debug=true;*/
		
		$sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       documento_id, ";
		$sql .= "       prefijo, ";
		$sql .= "       numero, ";
		$sql .= "       empresa_id, ";
		$sql .= "       codigo_proveedor_id, ";
		$sql .= "       usuario_id, ";
		$sql .= "       numero_factura, ";
		$sql .= "       valor_nota ";
		$sql .= "          				) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$documento_id.", ";
		$sql .= "        '".$prefijo."', ";
		$sql .= "        ".$numeracion.", ";
		$sql .= "        '".$Datos['datos']['empresa_id']."', ";
		$sql .= "        ".$Datos['codigo_proveedor_id'].", ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        '".$Datos['numero_factura']."', ";
		$sql .= "        ".$valor_nota." ";
		$sql .= "       ); ";
    
    
    foreach($DetalleTemporal as $key=>$valor)
    {
      		$sql .= "INSERT INTO ".$tabla."_d (";
      		$sql .= "       item_id,";
            $sql .= "       empresa_id, ";
      		$sql .= "       prefijo, ";
      		$sql .= "       numero, ";
      		$sql .= "       cantidad, ";
      		$sql .= "       porc_iva, ";
      		$sql .= "       codigo_producto, ";
      		$sql .= "       valor_concepto, ";
      		$sql .= "       valor_unitario, ";
      		$sql .= "       concepto, ";
      		$sql .= "       concepto_especifico, ";
      		$sql .= "       observacion, ";
      		$sql .= "       sube_baja_costo ";
      		$sql .= "          				) ";
      		$sql .= "VALUES ( ";
			$sql .= "        DEFAULT, ";
			$sql .= "        '".$Datos['datos']['empresa_id']."', ";
      		$sql .= "        '".$prefijo."', ";
      		$sql .= "        ".$numeracion.", ";
      		$sql .= "        ".$valor['cantidad'].", ";
      		$sql .= "        ".$valor['porc_iva'].", ";
      		$sql .= "        '".$valor['codigo_producto']."', ";
      		$sql .= "        ".$valor['valor_concepto'].", ";
      		$sql .= "        ".$valor['valor'].", ";
      		$sql .= "        '".trim($valor['concepto'])."', ";
      		$sql .= "        '".trim($valor['concepto_especifico'])."', ";
      		$sql .= "        '".$valor['observacion']."', ";
      		$sql .= "        '".$valor['sube_baja_costo']."' ";
      		$sql .= "       ); ";
          
          /*FUNCION PARA EL SUBE COSTO Y BAJA COSTO*/
          if($valor['sube_baja_costo']=='1')
		  {
		  if($signo=='-')/*Es Decir, si es Debito/Baja Costo*/
          $nuevo_costo = $valor['valor']-($valor['valor_concepto']/$valor['cantidad']);
          else
            $nuevo_costo = $valor['valor']+($valor['valor_concepto']/$valor['cantidad']);
          
          $sql .= "UPDATE inventarios ";
      		$sql .= "       SET ";
      		//$sql .= "       costo = ((costo+".$nuevo_costo.")/2), ";
      		$sql .= "       costo = ".$nuevo_costo.", ";
      		$sql .= "       costo_ultima_compra = ".$nuevo_costo." ";
      		$sql .= " where ";
      		$sql .= "        empresa_id = '".$Datos['datos']['empresa_id']."' ";
      		$sql .= "  and   codigo_producto = '".$valor['codigo_producto']."'; ";
		  }
          /*FIN FUNCION PARA EL SUBE COSTO Y BAJA COSTO*/
          
    }
	
		$sql .= "		UPDATE inv_facturas_proveedores ";
		$sql .= "		SET ";
		$sql .= "		saldo = (saldo ".$signo." ".$valor_nota."), ";
		$sql .= "		valor_notas_".$campo." = (valor_notas_".$campo." + ".$valor_nota.") ";
		$sql .= "		WHERE ";
		$sql .= "				  codigo_proveedor_id = ".$Datos['codigo_proveedor_id']." ";
		$sql .= "		AND		numero_factura = '".$Datos['numero_factura']."'; ";
    
		$sql .= "		UPDATE inv_facturas_proveedores ";
		$sql .= "		SET ";
		$sql .= "		sw_estado = '2' ";
		$sql .= "		WHERE ";
		$sql .= "				  codigo_proveedor_id = ".$Datos['codigo_proveedor_id']." ";
		$sql .= "		AND		numero_factura = '".$Datos['numero_factura']."' ";
		$sql .= "		AND		saldo <= 0; ";
		
		
		$sql .= "UPDATE documentos ";
		$sql .= "       SET ";
		$sql .= "       numeracion = numeracion + 1 ";
		$sql .= " where ";
		$sql .= "        empresa_id = '".$Datos['datos']['empresa_id']."' ";
		$sql .= " and    documento_id = ".$documento_id.";";
  
    //print_r($sql);
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$rst->Close();
		return true;
		}
  
  function Borrar_Temporal($Datos)
		{
           		//$this->debug=true;
        $sql .= "DELETE FROM inv_notas_facturas_proveedor_tmp ";
        $sql .= " where ";
        $sql .= "				  codigo_proveedor_id = ".$Datos['codigo_proveedor_id']." ";
        $sql .= "		AND		factura_proveedor = '".$Datos['numero_factura']."'; ";
        
    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;
    			$rst->Close();
    			return true;
    } 
	
	
	function GetTestingData(){
	
		/*$sql = "SELECT nota_debito_despacho_cliente_id AS nota_cliente_id, tipo, valor".
				" FROM public.notas_debito_despachos_clientes LIMIT 20";*/
				
		$sql ="    select 
                 *
                 from   
                 glosas_concepto_general
                 Order By codigo_concepto_general
				 ";
				
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			
		$datos = array();
		
		while(!$rst->EOF){
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
    	}
		
		$rst->Close();
		return $datos;

		
	
	}
    
	function Buscar_GlosasConceptoGeneral()
		{
           		//$this->debug=true;
				$sql ="    select 
                 *
                 from   
                 glosas_concepto_general
                 Order By codigo_concepto_general
				 ";

    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;

    			$datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    			return $datos;
    } 
	
	function Buscar_GlosasConceptoEspecifico($codigo_concepto_general)
			{
			//$this->debug=true;
			$sql ="    select 
			b.*
			from   
			glosas_concepto_general_especifico a,
			glosas_concepto_especifico b
			where
			a.codigo_concepto_general = '".$codigo_concepto_general."'
			and	a.codigo_concepto_especifico = b.codigo_concepto_especifico
			Order By b.codigo_concepto_especifico
			";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.

			while(!$rst->EOF) //Recorriendo el Vector;
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
			} 
      
	function Notas_Proveedor($Datos,$tabla,$filtro)
			{
			//$this->debug=true;
			$sql ="    
            select
            a.empresa_id,
            a.prefijo,
            a.numero,
            a.numero_factura,
            a.codigo_proveedor_id,
            a.fecha_registro,
            a.valor_nota,
            b.descripcion as documento,
            d.tipo_id_tercero,
            d.tercero_id,
            d.nombre_tercero,
            e.usuario,
			f.subtotal,
			f.iva_total,
			f.total,
			g.porc_rtf,
			g.porc_ica,
			g.porc_rtiva,
			TO_CHAR(g.fecha_registro,'YYYY') as anio_factura
            from
            ".$tabla." as a
            JOIN documentos as b ON (a.empresa_id = b.empresa_id)
            and (a.documento_id = b.documento_id)
            JOIN terceros_proveedores as c ON (a.codigo_proveedor_id = c.codigo_proveedor_id)
            JOIN terceros as d ON (c.tipo_id_tercero = d.tipo_id_tercero)
            and (c.tercero_id = d.tercero_id)
            JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
			JOIN (
				SELECT
				x.codigo_proveedor_id,
				x.numero_factura,
				SUM(((x.valor/((x.porc_iva/100)+1))*x.cantidad)) as subtotal,
				SUM(((x.valor-(x.valor/((x.porc_iva/100)+1)))*x.cantidad)) as iva_total,
				SUM((x.valor * x.cantidad)) as total
				FROM
				inv_facturas_proveedores_d as x
				WHERE
				x.numero_factura = '".$Datos['numero_factura']."'
				and x.codigo_proveedor_id = '".$Datos['codigo_proveedor_id']."'
				group by
				x.codigo_proveedor_id,
				x.numero_factura
			) as f ON (a.numero_factura = f.numero_factura)
			AND (a.codigo_proveedor_id = f.codigo_proveedor_id)
			JOIN inv_facturas_proveedores as g ON (a.numero_factura = g.numero_factura)
			AND (a.codigo_proveedor_id = g.codigo_proveedor_id)
            where
            a.numero_factura = '".$Datos['numero_factura']."'
            and a.codigo_proveedor_id = '".$Datos['codigo_proveedor_id']."'
            
			";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.

			while(!$rst->EOF) //Recorriendo el Vector;
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
			} 

      function Notas_DevolucionProveedor($Datos)
			{
			//$this->debug=true;
			$sql ="    
      select
      a.empresa_id,
      a.prefijo,
      a.numero,
      a.numero_factura,
      a.codigo_proveedor_id,
      f.fecha_registro,
      b.descripcion as documento,
      d.tipo_id_tercero,
      d.tercero_id,
      d.nombre_tercero,
      e.usuario
      from
      inv_bodegas_movimiento_devolucion_proveedor as a
      JOIN inv_bodegas_movimiento as f ON (a.empresa_id = f.empresa_id)
      and (a.prefijo = f.prefijo)
      and (a.numero = f.numero)
      JOIN documentos as b ON (f.empresa_id = b.empresa_id)
      and (f.documento_id = b.documento_id)
      JOIN terceros_proveedores as c ON (a.codigo_proveedor_id = c.codigo_proveedor_id)
      JOIN terceros as d ON (c.tipo_id_tercero = d.tipo_id_tercero)
      and (c.tercero_id = d.tercero_id)
      JOIN system_usuarios as e ON(e.usuario_id = f.usuario_id)
      where
      a.numero_factura = '".$Datos['numero_factura']."'
      and a.codigo_proveedor_id = '".$Datos['codigo_proveedor_id']."'
            
			";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.

			while(!$rst->EOF) //Recorriendo el Vector;
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
			} 
      
  function DetalleNota($Datos)
			{
			//$this->debug=true;
			$sql ="    
      Select
      a.codigo_producto,
      fc_descripcion_producto(a.codigo_producto) as descripcion,
      a.valor_concepto,
	  a.porc_iva,
      a.observacion,
      c.descripcion_concepto_general,
      d.descripcion_concepto_especifico,
      a.cantidad,
      a.valor_unitario,
	  ((a.porc_iva/100)*a.valor_concepto) as iva
      from
      inv_notas_credito_proveedor_d as a
      JOIN glosas_concepto_general_especifico as b ON(a.concepto = b.codigo_concepto_general)
      and (a.concepto_especifico = b.codigo_concepto_especifico)
      JOIN glosas_concepto_general as c ON (b.codigo_concepto_general = c.codigo_concepto_general)
      JOIN glosas_concepto_especifico as d ON (b.codigo_concepto_especifico = d.codigo_concepto_especifico)
      where
      a.empresa_id = '".$Datos['empresa_id']."'
      and a.prefijo = '".$Datos['prefijo']."'
      and a.numero = '".$Datos['numero']."'
      UNION
      Select
      a.codigo_producto,
      fc_descripcion_producto(a.codigo_producto) as descripcion,
      a.valor_concepto,
	  a.porc_iva,
      a.observacion,
      c.descripcion_concepto_general,
      d.descripcion_concepto_especifico,
      a.cantidad,
      a.valor_unitario,
	  ((a.porc_iva/100)*a.valor_concepto) as iva
      from
      inv_notas_debito_proveedor_d as a
      JOIN glosas_concepto_general_especifico as b ON(a.concepto = b.codigo_concepto_general)
      and (a.concepto_especifico = b.codigo_concepto_especifico)
      JOIN glosas_concepto_general as c ON (b.codigo_concepto_general = c.codigo_concepto_general)
      JOIN glosas_concepto_especifico as d ON (b.codigo_concepto_especifico = d.codigo_concepto_especifico)
      where
      a.empresa_id = '".$Datos['empresa_id']."'
      and a.prefijo = '".$Datos['prefijo']."'
      and a.numero = '".$Datos['numero']."'
            
			";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.

			while(!$rst->EOF) //Recorriendo el Vector;
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
			} 
  
  
  /**
		* Funcion donde se obtiene el nombre de un usuario
		*
    * @param int $usuario Identificacion del usuario
		*
    * @return mixed
    */
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
  	
  function AnularNota($Formulario)
	{
      $sql  = "UPDATE inv_notas_facturas_proveedor ";
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
	
	
	/*************************************************************************************
	*  Funcion obtener detalle de facturas proveedores - cruce con actas
	*  tecnicas
	*  @param $fac : numero factura proveedor, $prov: codigo proveedor
	*  return array $datos.
	**************************************************************************************/
	function Obtener_DetFactura($fac,$prov,$emp)
    {	
	 $sql  ="	SELECT fp.empresa_id, fp.centro_utilidad, fp.bodega, rpd.codigo_producto, ";
	 $sql .="				  fc_descrip_producto(rpd.codigo_producto) AS descripcion, ";
	 $sql .="				  rpd.cantidad, ";
	 $sql .="				  rpd.valor, ";
	 $sql .="				  rpd.lote, ";
	 $sql .="				  rpd.fecha_vencimiento, ";
	 $sql .="    			  rp.prefijo, ";
	 $sql .=" 			      rp.numero, ";
	 $sql .="				  rpd.porc_iva, ";
	 $sql .="				  CASE WHEN  rpd.codigo_producto IN ";
	 $sql .="							(SELECT rpd.codigo_producto ";
	 $sql .="							 FROM inv_recepciones_parciales_d rpd ";
	 $sql .="							   JOIN inv_recepciones_parciales rp ";
	 $sql .="								  ON (rp.recepcion_parcial_id = rpd.recepcion_parcial_id) ";
	 $sql .="							    JOIN esm_acta_tecnica eat ";
	 $sql .="								   ON (  ";
	 $sql .="										  rp.empresa_id = eat.empresa_id ";
	 $sql .="										  AND rp.prefijo = eat.prefijo ";
	 $sql .="										  AND rp.numero = eat.numero) ";
	 $sql .="										  WHERE ";  
	 $sql .="											 rp.empresa_id = eat.empresa_id ";
	 $sql .="											 AND rp.prefijo = eat.prefijo ";
	 $sql .="											 AND rp.numero = eat.numero ";           			
	 $sql .="											 AND rpd.codigo_producto = eat.codigo_producto  ";
	 $sql .="											 AND rpd.lote = eat.lote)  ";
	 $sql .="					 THEN '1' ELSE '0' END AS estado_acta ";
	 $sql .="	FROM    inv_recepciones_parciales rp ";
	 $sql .="	  JOIN    inv_recepciones_parciales_d rpd ";
	 $sql .="	     ON   (rp.recepcion_parcial_id = rpd.recepcion_parcial_id) ";
	 $sql .="	  JOIN    inv_facturas_proveedores_d fpd ";
	 $sql .="	     ON   (rpd.recepcion_parcial_id = fpd.recepcion_parcial_id) ";
	 $sql .="	  JOIN    inv_facturas_proveedores fp ";
	 $sql .="	     ON   (fp.numero_factura = fpd.numero_factura AND fp.codigo_proveedor_id = fpd.codigo_proveedor_id) ";
	 $sql .="  WHERE   fpd.numero_factura ='".$fac."'  ";
	 $sql .=" 	   AND   fpd.codigo_proveedor_id = ".$prov." ";
	 $sql .="	   AND   rp.recepcion_parcial_id = rpd.recepcion_parcial_id ";
	 $sql .="	   AND   rp.recepcion_parcial_id = rpd.recepcion_parcial_id ";
	 $sql .="      AND    rp.empresa_id = '".$emp."'  ";
	 $sql .="  GROUP BY  1,2,3,4,5,6,7,8,9,10,11,12 ";	
	
	 if(!$rst = $this->ConexionBaseDatos($sql))
	    return false;
	
	 $datos = array();
	 while(!$rst->EOF)
	 {
	  $datos[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
	 $rst->Close();
	 
	 return $datos;
	}
	
	
	
	
  }
?>