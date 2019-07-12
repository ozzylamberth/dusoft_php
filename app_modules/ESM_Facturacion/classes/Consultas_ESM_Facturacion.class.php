<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_TipoEvento.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class Consultas_ESM_Facturacion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_ESM_Facturacion(){}
 
   
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Obtener_TrasladosESM($empresa_id,$fecha_inicio,$fecha_final)
		{
        $filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		//$this->debug=true;
    		$sql = "
        select codigo_producto,
               sw_bodegamindefensa,
               sw_entregado_off,
               SUM(numero_unidades) as total
               from
                   (
                   select
                   dd.codigo_producto,
                   dc.sw_bodegamindefensa,
                   dc.sw_entregado_off,
                   SUM(dd.cantidad) as numero_unidades
                   FROM
                       inv_bodegas_movimiento_traslados_esm as dc,
                       inv_bodegas_movimiento as d,
                       inv_bodegas_movimiento_d AS dd
                   WHERE
                              dc.empresa_id = d.empresa_id
                   and        dc.prefijo = d.prefijo
                   and        dc.numero = d.numero
                   and        dc.sw_facturado = '0'
                   and        dc.empresa_id_factura IS NULL
                              ".$filtro."
                   and        d.empresa_id = dd.empresa_id
                   and        d.prefijo = dd.prefijo
                   and        d.numero = dd.numero
                   group by dd.codigo_producto,dc.sw_bodegamindefensa,dc.sw_entregado_off
                   ) as A
                  group by codigo_producto,sw_bodegamindefensa,sw_entregado_off ";

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
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Obtener_DespachosESM($empresa_id,$fecha_inicio,$fecha_final)
		{
    		$filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
        //$this->debug=true;
    		$sql = "select codigo_producto,
                       sw_bodegamindefensa,
                       sw_entregado_off,
                               SUM(numero_unidades) as total
                               from
                                   (
                                   select
                                   dd.codigo_producto,
                                   dc.sw_bodegamindefensa,
                                   sw_entregado_off,
                                   SUM(dd.cantidad) as numero_unidades
                                   FROM
                                       inv_bodegas_movimiento_despacho_campania as dc,
                                       inv_bodegas_movimiento as d,
                                       inv_bodegas_movimiento_d AS dd
                                   WHERE
                                              dc.empresa_id = d.empresa_id
                                   and        dc.prefijo = d.prefijo
                                   and        dc.numero = d.numero
                                   and        dc.empresa_id_factura IS NULL
                                   ".$filtro."
                                   and        d.empresa_id = dd.empresa_id
                                   and        d.prefijo = dd.prefijo
                                   and        d.numero = dd.numero
                                   group by dd.codigo_producto,dc.sw_bodegamindefensa,dc.sw_entregado_off
                                   ) as A
                                  group by codigo_producto,sw_bodegamindefensa,sw_entregado_off ";

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

   /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Obtener_DispensacionESM($empresa_id,$fecha_inicio,$fecha_final)
		{
    $filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
    $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		//$this->debug=true;
    		$sql = "select codigo_producto,
       SUM(numero_unidades) as total,
       SUM(costo) as valor
       from
           (
           select
           dd.codigo_producto,
           SUM(dd.cantidad) as numero_unidades,
           SUM(dd.total_costo) as costo
           FROM
               esm_formulacion_despachos_medicamentos as dc,
               bodegas_documentos as d,
               bodegas_documentos_d AS dd
           WHERE
                      dc.bodegas_doc_id = d.bodegas_doc_id
           and        dc.numeracion = d.numeracion
           and        dc.empresa_id_factura IS NULL
            ".$filtro."
           and        dc.sw_facturado = '0'
           and        d.bodegas_doc_id = dd.bodegas_doc_id
           and        d.numeracion = dd.numeracion
           group by(dd.codigo_producto)
           ) as A
      group by (codigo_producto) ";

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


/*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Obtener_DispensacionPendientesESM($empresa_id,$fecha_inicio,$fecha_final)
		{
    		$filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		//$this->debug=true;
    		$sql = "
              select codigo_producto,
               SUM(numero_unidades) as total,
               SUM(costo) as valor
               from
                   (
                   select
                   dd.codigo_producto,
                   SUM(dd.cantidad) as numero_unidades,
                   SUM(dd.total_costo) as costo
                   FROM
                       esm_formulacion_despachos_medicamentos_pendientes as dc,
                       bodegas_documentos as d,
                       bodegas_documentos_d AS dd
                   WHERE
                              dc.bodegas_doc_id = d.bodegas_doc_id
                   and        dc.numeracion = d.numeracion
                   and        dc.empresa_id_factura IS NULL
                   ".$filtro."
                   and        d.bodegas_doc_id = dd.bodegas_doc_id
                   and        d.numeracion = dd.numeracion
                   group by(dd.codigo_producto)
                   ) as A
            group by (codigo_producto)
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

   /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function ObtenerContratoId($empresa_id)
		{
			//$this->debug=true;
      $sql = "SELECT pc.plan_id, pl.*,
              lp.codigo_lista
              FROM esm_parametros_contrato as pc
              JOIN planes as pl ON (pc.plan_id = pl.plan_id) and (pl.estado = '1')
              JOIN listas_precios as lp ON (pl.lista_precios = lp.codigo_lista) and (lp.codigo_lista = 
              (
              select lpd.codigo_lista
              from
              listas_precios_detalle as lpd
              where
              empresa_id= '".$empresa_id."'
              group by(lpd.codigo_lista)
              ))
              where pc.empresa_id IS NULL
              and pc.sw_estado = '1'; ";
           
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
   /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function InformacionDocumento($empresa_id,$documento_id)
		{
			//$this->debug=true;
      $sql = "SELECT *
                    FROM documentos ";
      $sql .= " where ";
      $sql .= " empresa_id= '".$empresa_id."' ";
      $sql .= "and documento_id=".$documento_id." ";
           
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function AgrupacionProductos($empresa_id,$documento_id)
		{
			//$this->debug=true;
      $sql = "select codigo_producto,
              sum(cantidad) as total_cantidad,
              sum(valor) as valor_total,
             (sum(valor)/sum(cantidad)) as valor_unitario
        FROM
          (
                   select
                   codigo_producto,
                   SUM(cantidad) as cantidad,
                   SUM(valor) as valor
                   FROM
                       esm_facturacion_temporal_traslados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                   group by codigo_producto
                  UNION
                   select
                   codigo_producto,
                   SUM(cantidad) as cantidad,
                   SUM(valor) as valor
                   FROM
                       esm_facturacion_temporal_despachados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                   group by codigo_producto
                   UNION
                   select
                   codigo_producto,
                   cantidad,
                   valor
                   FROM
                       esm_facturacion_temporal_dispensados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                   UNION
                   select
                   codigo_producto,
                   cantidad,
                   valor
                   FROM
                       esm_facturacion_temporal_dispensados_pendientes
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
              ) AS A
         group by (codigo_producto);   ";
           
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
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Buscar_PrecioProducto_Lista($empresa_id,$lista_precios,$codigo_producto)
		{
			////$this->debug=true;
      $sql = "SELECT 
                    precio,
                    valor_inicial
                   FROM listas_precios_detalle ";
      $sql .= " where ";
      $sql .= "           codigo_lista = '".$lista_precios."' ";
      $sql .= "      AND  empresa_id = '".$empresa_id."' ";
      $sql .= "      AND  codigo_producto = '".$codigo_producto."' ";
                 
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Buscar_PrecioProducto_ListaBase($empresa_id,$lista_precios,$codigo_producto)
		{
			////$this->debug=true;
      $sql = "SELECT 
                   precio,
                   valor_inicial
                   FROM listas_precios_base ";
      $sql .= " where ";
      $sql .= "           codigo_lista = '".$lista_precios."' ";
      $sql .= "      AND  empresa_id = '".$empresa_id."' ";
      $sql .= "      AND  codigo_producto = '".$codigo_producto."' ";
                 
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Buscar_PrecioProducto_Inventario($empresa_id,$codigo_producto)
		{
			////$this->debug=true;
      $sql = "SELECT 
                    costo as precio
                   FROM inventarios ";
      $sql .= " where ";
      $sql .= "          empresa_id = '".$empresa_id."' ";
      $sql .= "    and   codigo_producto = '".$codigo_producto."' ";
                 
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Buscar_CabeceraTemporal($empresa_id,$documento_id)
		{
			////$this->debug=true;
      $sql = "SELECT 
                    eft.*,
                    t.nombre_tercero
                   FROM  
                   terceros t,
                   esm_facturacion_temporal eft ";
      $sql .= " where ";
      $sql .= "          eft.empresa_id = '".$empresa_id."' ";
      $sql .= "    and   eft.documento_id = ".$documento_id." ";
      $sql .= "    and   eft.tipo_id_tercero = t.tipo_id_tercero ";
      $sql .= "    and   eft.tercero_id = t.tercero_id ";
                 
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Buscar_DetalleTemporal($empresa_id,$documento_id,$tabla)
		{
			////$this->debug=true;
      $sql = "SELECT 
                    fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                    * ";
      $sql .= " FROM ".$tabla." ";
      $sql .= " where ";
      $sql .= "          empresa_id = '".$empresa_id."' ";
      $sql .= "    and   documento_id = ".$documento_id." ";
      $sql .= "     order by codigo_producto ";          
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
  
     
       /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Borrar_Item($orden_requisicion_tmp_id,$codigo_producto)
		{
			////$this->debug=true;
      $sql = " delete from esm_orden_requisicion_tmp_d ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id." ";
      $sql .= " and    codigo_producto = '".$codigo_producto."' ";
      
			////$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    
		$rst->Close();
		return true;
		}
	
  
  function Insertar_CabeceraTemporal($request,$plan,$fecha_inicio,$fecha_fin)
		{
	     
    $sql  = "INSERT INTO esm_facturacion_temporal (";
		$sql .= "       documento_id, ";
		$sql .= "       empresa_id, ";
		$sql .= "       fecha_fin, ";
		$sql .= "       fecha_inicio, ";
		$sql .= "       plan_id, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
    $sql .= "       usuario_id ";
    $sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$request['datos']['ssiid'].", ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        '".$fecha_fin."', ";
		$sql .= "        '".$fecha_inicio."', ";
		$sql .= "        ".$plan['plan_id'].", ";
		$sql .= "        '".$plan['tipo_tercero_id']."', ";
		$sql .= "        '".$plan['tercero_id']."', ";
    $sql .= "        ".UserGetUID()." ";
		$sql .= "       ); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

    $rst->Close();
		return true;
		}
    
    
    function Insertar_DetalleTemporal_1($request,$codigo_producto,$cantidad,$plan_id,$tabla,$sw_bodegamindefensa,$sw_entregado_off)
		{ //$_REQUEST,$valor['codigo_producto'],$plan['plan_id'],"esm_facturacion_temporal_traslados"
			//$this->debug=true;
    
    $sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       empresa_id, ";
		$sql .= "       documento_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad, ";
		$sql .= "       valor, ";
		$sql .= "       valor_unitario, ";
		$sql .= "       porcentaje_intermediacion, ";
		$sql .= "       sw_bodegamindefensa, ";
		$sql .= "       sw_entregado_off ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        ".$request['datos']['ssiid'].", ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        ".$cantidad.", ";
		$sql .= "        ".($cantidad."*(select fc_precio_producto_plan(".$plan_id.",'".$codigo_producto."','".$request['datos']['empresa_id']."','".$sw_bodegamindefensa."','".$sw_entregado_off."')),");
		$sql .= "        (select fc_precio_producto_plan('".$plan_id."','".$codigo_producto."','".$request['datos']['empresa_id']."','".$sw_bodegamindefensa."','".$sw_entregado_off."')), ";
		$sql .= "        0,";
		$sql .= "        '".$sw_bodegamindefensa."', ";
		$sql .= "        '".$sw_entregado_off."' ";
		$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();		
		return true;
		}
    
  function Insertar_DetalleTemporal($request,$codigo_producto,$cantidad,$valor_unitario,$tabla,$porcentaje)
		{
			//$this->debug=true;
    if($porcentaje>0)
            {
            $porc = ($porcentaje/100)+1;
            $valor= ($valor_unitario/$porc)+$valor_unitario;
            }
            else
                {
                $valor=$valor_unitario;
                }
    $sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       empresa_id, ";
		$sql .= "       documento_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad, ";
		$sql .= "       valor, ";
		$sql .= "       valor_unitario, ";
		$sql .= "       porcentaje_intermediacion ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        ".$request['datos']['ssiid'].", ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        ".$cantidad.", ";
		$sql .= "        ".($cantidad*$valor).", ";
		$sql .= "        ".$valor_unitario.", ";
		$sql .= "        ".$porcentaje." ";
		$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    
    $rst->Close();
		return true;
		}

  
 
    

 function Insertar_Factura($request,$Documento,$DATOS)
		{
	     
    $sql  = "INSERT INTO fac_facturas (";
		$sql .= "       empresa_id, ";
		$sql .= "       prefijo, ";
		$sql .= "       factura_fiscal, ";
		$sql .= "       estado, ";
		$sql .= "       usuario_id, ";
		$sql .= "       fecha_registro, ";
		$sql .= "       plan_id, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       documento_id, ";
    $sql .= "       tipo_factura ";
    $sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        '".$Documento['prefijo']."', ";
		$sql .= "        ".$Documento['numeracion'].", ";
		$sql .= "        '0', ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        NOW(), ";
		$sql .= "        ".$DATOS['plan_id'].", ";
		$sql .= "        '".$DATOS['tipo_id_tercero']."', ";
		$sql .= "        '".$DATOS['tercero_id']."', ";
    $sql .= "        ".$request['datos']['ssiid'].", ";
    $sql .= "        '7' ";
		$sql .= "       ); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
    
   function Insertar_DetalleFactura($valor,$Documento)
		{
		
    $sql  = "INSERT INTO fac_facturas_formulas (";
		$sql .= "       empresa_id, ";
		$sql .= "       prefijo, ";
		$sql .= "       factura_fiscal, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       precio, ";
		$sql .= "       valor_total, ";
		$sql .= "       cantidad ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$Documento['empresa_id']."', ";
		$sql .= "        '".$Documento['prefijo']."', ";
		$sql .= "        ".$Documento['numeracion'].", ";
		$sql .= "        '".$valor['codigo_producto']."', ";
		$sql .= "        ".$valor['valor_unitario'].", ";
		$sql .= "        ".$valor['valor_total'].", ";
		$sql .= "        ".$valor['total_cantidad']." ";
		$sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
        

  
    function Estado_DESPACHOS_TRASLADOS($empresa_id,$fecha_inicio,$fecha_final,$documento,$tabla)
		{
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		//$this->debug=true;
    		$sql = "
                   UPDATE 
                       ".$tabla." a
                       SET
                       empresa_id_factura = '".$empresa_id."',
                       prefijo_factura = '".$documento['prefijo']."',
                       factura_fiscal = ".$documento['numeracion']."
                       FROM
                       inv_bodegas_movimiento as b
                       WHERE
                              a.empresa_id_factura IS NULL
                       and    a.empresa_id = b.empresa_id       
                       and    a.prefijo = b.prefijo       
                       and    a.numero = b.numero     
                             ".$filtro." ";
          	////$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
    function Estado_DISPENSADOS_PENDIENTES($empresa_id,$fecha_inicio,$fecha_final,$documento,$tabla)
		{
        //esm_formulacion_despachos_medicamentos
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		//$this->debug=true;
    		$sql = "
                   UPDATE 
                       ".$tabla." a
                       SET
                       empresa_id_factura = '".$empresa_id."',
                       prefijo_factura = '".$documento['prefijo']."',
                       factura_fiscal = ".$documento['numeracion']."
                       FROM
                       bodegas_documentos as b
                       WHERE
                              a.empresa_id_factura IS NULL
                       and    a.bodegas_doc_id = b.bodegas_doc_id       
                       and    a.numeracion = b.numeracion       
                       ".$filtro." ";
          	////$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
     function Asignar_ValorTotal($empresa_id,$documento_id,$documento,$total_factura)
		{
		
    $sql  = "UPDATE fac_facturas ";
		$sql .= "       SET ";
		$sql .= "       total_factura = ".$total_factura.", ";
		$sql .= "       saldo = ".$total_factura." ";
		$sql .= " where ";
    $sql .= "        empresa_id = '".$empresa_id."' ";
    $sql .= " and    prefijo = '".$documento['prefijo']."' ";
    $sql .= " and    factura_fiscal = ".$documento['numeracion']."; ";
    
    $sql .= "UPDATE documentos ";
		$sql .= "       SET ";
		$sql .= "       numeracion = numeracion + 1 ";
		$sql .= " where ";
    $sql .= "        empresa_id = '".$empresa_id."' ";
    $sql .= " and    documento_id = ".$documento_id.";";
  
    
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
    
    function Borrar_Temporal($empresa_id,$documento_id)
		{
			//$this->debug=true;
      $sql = " delete from esm_facturacion_temporal ";
      $sql .= " where ";
      $sql .= "         documento_id = ".$documento_id." ";
      $sql .= "    and  empresa_id = '".$empresa_id."'; ";
         
			////$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
    
    
     /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function BuscarFacturas($empresa_id,$documento_id,$factura,$fecha_inicio_,$fecha_final_,$prefijo,$numero)
		{
        if($fecha_inicio_!="" && $fecha_final_!="")
        {
        $fecha_i=explode("/",$fecha_inicio_);
        $fecha_inicio=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
        $fecha_f=explode("/",$fecha_final_);
        $fecha_final=$fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
        
        $filtro = "  AND       f.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       f.fecha_registro <= '".$fecha_final." 24:00:00' ";
        }        
        
        if($prefijo != "" && $numero != "")
        {
        $filtro .= "  AND       f.prefijo = '".$prefijo."' ";
        $filtro .= " AND       f.factura_fiscal = ".$numero." ";
        }
    		//$this->debug=true;
    	$sql ="    select 
                 f.*,
                 t.nombre_tercero,
                 u.nombre
                 from   
                 fac_facturas f,
                 terceros t,
                 system_usuarios u
                 where
                     f.empresa_id = '".$empresa_id."'
                 and f.estado = '0'
                 and f.usuario_id = u.usuario_id
                 ".$filtro."
                 and f.tipo_id_tercero = t.tipo_id_tercero
                 and f.tercero_id = t.tercero_id
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
	
	function Buscar_GlosasFacturas($empresa_id,$prefijo,$factura_fiscal)
	{
	//$this->debug=true;
	$sql ="    select 
	*
	from   
	esm_glosa_factura
	where
		sw_estado = '1'
	and	prefijo = '".$prefijo."'
	and	factura_fiscal = ".$factura_fiscal."
	and	empresa_id = '".$empresa_id."'
	";

	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$datos = array(); //Definiendo que va a ser un arreglo.

	while(!$rst->EOF) //Recorriendo el Vector;
	{
	$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
	$rst->MoveNext();
	}
	$rst->Close();
	return $datos;
	} 
	
	function BuscarFactura($empresa_id,$prefijo,$factura_fiscal)
		{
       		//$this->debug=true;
    	$sql ="    select 
                 f.*,
                 t.nombre_tercero,
                 u.nombre,
				 p.*
                 from   
                 fac_facturas f,
                 terceros t,
				 planes p,
                 system_usuarios u
                 where
                     f.empresa_id = '".$empresa_id."'
                 and f.prefijo = '".$prefijo."'
                 and f.factura_fiscal = ".$factura_fiscal."
                 and f.usuario_id = u.usuario_id
                 and f.tipo_id_tercero = t.tipo_id_tercero
                 and f.tercero_id = t.tercero_id
                 and f.plan_id = p.plan_id
                 ";

    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;

    			$datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    			return $datos;
    } 
	
	function Buscar_GlosasMotivos()
		{
           		//$this->debug=true;
				$sql ="    select 
                 *
                 from   
                 glosas_motivos
                 Order By motivo_glosa_id
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
			
			
		function Insertar_GlosaFactura($request,$fecha_glosa)
		{
		if($request['sw_mayor_valor']=="")
			$sw_mayor_valor = "0";
			else
			$sw_mayor_valor = $request['sw_mayor_valor'];
			
		if($request['sw_glosa_total_factura']=="")
			$sw_glosa_total_factura = "0";
			else
			$sw_glosa_total_factura = $request['sw_glosa_total_factura'];
		
		$sql  = "INSERT INTO esm_glosa_factura (";
		$sql .= "       esm_glosa_id, ";
		$sql .= "       empresa_id, ";
		$sql .= "       prefijo, ";
		$sql .= "       factura_fiscal, ";
		$sql .= "       fecha_glosa, ";
		$sql .= "       motivo_glosa_id, ";
		$sql .= "       observacion, ";
		$sql .= "       documento_interno_cliente_id, ";
		$sql .= "       usuario_id, ";
		$sql .= "       codigo_concepto_general, ";
		$sql .= "       codigo_concepto_especifico, ";
		$sql .= "       sw_mayor_valor, ";
		$sql .= "       sw_glosa_total_factura ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        '".$request['prefijo']."', ";
		$sql .= "        ".$request['factura_fiscal'].", ";
		$sql .= "        '".$fecha_glosa."', ";
		$sql .= "        '".$request['motivo_glosa_id']."', ";
		$sql .= "        '".$request['observacion']."', ";
		$sql .= "        '".$request['documento_interno_cliente_id']."', ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        '".$request['codigo_concepto_general']."', ";
		$sql .= "        '".$request['codigo_concepto_especifico']."', ";
		$sql .= "        '".$sw_mayor_valor."', ";
		$sql .= "        '".$sw_glosa_total_factura."' ";
		$sql .= "       )RETURNING(esm_glosa_id); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
			{
			$datos = array(); //Definiendo que va a ser un arreglo.

			while(!$rst->EOF) //Recorriendo el Vector;
			{
			$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
			}
		

		$rst->Close();
		}

		function Buscar_GlosaActiva($esm_glosa_id)
		{
		//$this->debug=true;
		$sql ="    select 
		gce.descripcion_concepto_especifico,
		gcg.descripcion_concepto_general,
		mg.motivo_glosa_descripcion,
		gf.*,
		f.*,
		t.nombre_tercero,
		u.nombre,
		p.*,
		f.empresa_id as empresa_id_factura,
    gf.observacion
		from   
		glosas_concepto_general gcg,
		glosas_concepto_especifico gce,
		glosas_motivos mg,
		esm_glosa_factura gf,
		fac_facturas f,
		terceros t,
		planes p,
		system_usuarios u
		where
			gf.esm_glosa_id = ".$esm_glosa_id."
		and	gf.motivo_glosa_id = mg.motivo_glosa_id
		and	gf.codigo_concepto_general = gcg.codigo_concepto_general
		and	gf.codigo_concepto_especifico = gce.codigo_concepto_especifico
		and	gf.sw_estado = '1'
		and	gf.empresa_id = f.empresa_id
		and gf.prefijo = f.prefijo
		and gf.factura_fiscal = f.factura_fiscal
		and f.usuario_id = u.usuario_id
		and f.tipo_id_tercero = t.tipo_id_tercero
		and f.tercero_id = t.tercero_id
		and f.plan_id = p.plan_id
		";

		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

		$datos = array(); //Definiendo que va a ser un arreglo.

		while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
		} 
		
		function DetalleFactura($DATOS,$empresa_id,$esm_glosa_id)
		{
		//$this->debug=true;
		$sql ="    select 
		fc_descripcion_producto_alterno(codigo_producto) as producto,
		*
		from   
			fac_facturas_formulas
		where
				empresa_id = '".$empresa_id."'
			and	prefijo = '".$DATOS['prefijo']."'
			and	factura_fiscal = '".$DATOS['factura_fiscal']."'
			and codigo_producto NOT IN (select 
											codigo_producto
											from
											esm_glosa_factura_productos
											where
													esm_glosa_id = ".$esm_glosa_id."
											and	sw_estado in ('1','3')
										)
										
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

		function Insertar_GlosaDetalle($motivo_glosa_id,$valor_glosa,$codigo_concepto_general,$codigo_concepto_especifico,$observacion,$codigo_producto,$esm_glosa_id)
		{
		$sql  = "INSERT INTO esm_glosa_factura_productos  (";
		$sql .= "       esm_glosa_detalle_id, ";
		$sql .= "       esm_glosa_id, ";
		$sql .= "       motivo_glosa_id, ";
		$sql .= "       observacion, ";
		$sql .= "       valor_glosa, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       codigo_concepto_general, ";
		$sql .= "       codigo_concepto_especifico, ";		
		$sql .= "       usuario_id ";		
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        ".$esm_glosa_id.", ";
		$sql .= "        '".$motivo_glosa_id."', ";
		$sql .= "        '".$observacion."', ";
		$sql .= "        ".$valor_glosa.", ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        '".$codigo_concepto_general."', ";
		$sql .= "        '".$codigo_concepto_especifico."',";
		$sql .= "        ".UserGetUID()." ";
		$sql .= "       ); ";			
		//$this->debug=true;
			////$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
		
		function Actualizar_ValorGlosa($esm_glosa_id)
		{
		
		$sql = "
		UPDATE 	esm_glosa_factura
		SET
		valor_glosa = COALESCE(( select sum(valor_glosa)
                  from
                  esm_glosa_factura_productos
                  where
                  esm_glosa_id = ".$esm_glosa_id."
                  and	sw_estado in ('1','3')
                  ),0),
    valor_aceptado = COALESCE(( select sum(valor_aceptado)
                  from
                  esm_glosa_factura_productos
                  where
                  esm_glosa_id = ".$esm_glosa_id."
                  and	sw_estado = '3'
                  ),0),
    valor_no_aceptado = COALESCE(( select sum(valor_no_aceptado)
                  from
                  esm_glosa_factura_productos
                  where
                  esm_glosa_id = ".$esm_glosa_id."
                  and	sw_estado = '3'
                  ),0),
    auditor_id = ".UserGetUID()."
		where
			esm_glosa_id = ".$esm_glosa_id."
		";
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
		
	function Buscar_DetalleGlosa($esm_glosa_id)
		{
		//$this->debug=true;
		$sql ="    		select
						fc_descripcion_producto_alterno(gp.codigo_producto) as producto,
						gp.*,
						gm.motivo_glosa_descripcion,
						gce.descripcion_concepto_especifico,
						gcg.descripcion_concepto_general
						
						from
						esm_glosa_factura_productos gp,
						glosas_motivos gm,
						glosas_concepto_especifico gce,
						glosas_concepto_general gcg
						where
								  gp.esm_glosa_id = ".$esm_glosa_id."
						and		gp.sw_estado in ('1','3')
						and		gp.motivo_glosa_id = gm.motivo_glosa_id
						and		gp.codigo_concepto_especifico = gce.codigo_concepto_especifico
						and		gp.codigo_concepto_general = gcg.codigo_concepto_general
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
    
    function Seleccionar_DetalleGlosa($esm_glosa_detalle_id,$esm_glosa_id)
		{
		//$this->debug=true;
		$sql ="    		select
						fc_descripcion_producto_alterno(gp.codigo_producto) as producto,
						gp.*,
						gm.motivo_glosa_descripcion,
						gce.descripcion_concepto_especifico,
						gcg.descripcion_concepto_general
						from
						esm_glosa_factura_productos gp,
						glosas_motivos gm,
						glosas_concepto_especifico gce,
						glosas_concepto_general gcg
						where
                  gp.esm_glosa_id = ".$esm_glosa_id."
						and		gp.esm_glosa_detalle_id = ".$esm_glosa_detalle_id."
						and		gp.sw_estado IN ('1','3')
						and		gp.motivo_glosa_id = gm.motivo_glosa_id
						and		gp.codigo_concepto_especifico = gce.codigo_concepto_especifico
						and		gp.codigo_concepto_general = gcg.codigo_concepto_general
		";

		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

		$datos = array(); //Definiendo que va a ser un arreglo.

		while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
		}
    
    function AceptarGlosa($Formulario)
		{
		//$this->debug=true;
		$sql =" UPDATE
						esm_glosa_factura_productos
            set 
            sw_estado = '3',
            valor_no_aceptado = COALESCE(".$Formulario['valor_no_aceptado'].",0),
            valor_aceptado = COALESCE(".$Formulario['valor_aceptado'].",0)
						where
                  esm_glosa_id = ".$Formulario['esm_glosa_id']."
						and		esm_glosa_detalle_id = ".$Formulario['esm_glosa_detalle_id']."
						and		sw_estado = '1'
					";

		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
    
    function AnularGlosaDetalle($Formulario)
		{
		//$this->debug=true;
		$sql =" UPDATE
						esm_glosa_factura_productos
            set 
            sw_estado = '0'
						where
                  esm_glosa_id = ".$Formulario['esm_glosa_id']."
						and		esm_glosa_detalle_id = ".$Formulario['esm_glosa_detalle_id']."
					";

		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$rst->Close();
		return true;
		}
    
    function AnularGlosa($esm_glosa_id)
		{
		//$this->debug=true;
		$sql =" UPDATE
						esm_glosa_factura
            set 
            sw_estado = '0'
						where
                  esm_glosa_id = ".$esm_glosa_id."
					
					";

		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$rst->Close();
		return true;
		}
    
    function Cambiar_TipoGlosa($esm_glosa_id,$sw_glosa_total_factura)
		{
		//$this->debug=true;
		$sql =" UPDATE
						esm_glosa_factura
            set 
            sw_glosa_total_factura = '".$sw_glosa_total_factura."',
            valor_glosa = COALESCE((select f.saldo
                                    from fac_facturas f,
                                         esm_glosa_factura gf
                                    where
                                              gf.esm_glosa_id = ".$esm_glosa_id."
                                          and gf.empresa_id = f.empresa_id
                                          and gf.prefijo  = f.prefijo
                                          and gf.factura_fiscal  = f.factura_fiscal
                                    )
                                  ,0),
            valor_aceptado = 0,
            valor_no_aceptado = 0
						where
                  esm_glosa_id = ".$esm_glosa_id."
					";

		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
    
	 function Aceptar_GlosaTotal($Formulario)
		{
		//$this->debug=true;
		$sql =" UPDATE
						esm_glosa_factura
            set 
            valor_no_aceptado = COALESCE(".$Formulario['valor_no_aceptado'].",0),
            valor_aceptado = COALESCE(".$Formulario['valor_aceptado'].",0)
						where
                  esm_glosa_id = ".$Formulario['esm_glosa_id']."
						";

		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$rst->Close();
		return true;
		}
    
    function DocumentoNota($empresa_id,$sw_nota_credito)
		{
		//$this->debug=true;
		$sql ="    		select
						ep.*,
						doc.*
						from
						esm_parametros_glosas ep,
						documentos doc
						where
								ep.empresa_id = '".$empresa_id."'
						and		ep.sw_nota_credito = '".$sw_nota_credito."'
						and		ep.documento_id = doc.documento_id
					";

		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

		$datos = array(); //Definiendo que va a ser un arreglo.

		while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
		}
    
		function GuardarTransaccion($DetalleGlosa,$DocumentoNota,$tabla,$signo)
		{
		//$this->debug=true;
		//print_r($DetalleGlosa);
		$sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       documento_id, ";
		$sql .= "       prefijo, ";
		$sql .= "       numero, ";
		$sql .= "       empresa_id, ";
		$sql .= "       esm_glosa_id, ";
		$sql .= "       usuario_id, ";
		$sql .= "       valor_glosa, ";
		$sql .= "       valor_aceptado, ";
		$sql .= "       valor_no_aceptado, ";
		$sql .= "       observacion ";
		$sql .= "          				) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$DocumentoNota['documento_id'].", ";
		$sql .= "        '".$DocumentoNota['prefijo']."', ";
		$sql .= "        ".$DocumentoNota['numeracion'].", ";
		$sql .= "        '".$DocumentoNota['empresa_id']."', ";
		$sql .= "        ".$DetalleGlosa['esm_glosa_id'].", ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        ".$DetalleGlosa['valor_glosa'].", ";
		$sql .= "        ".$DetalleGlosa['valor_aceptado'].", ";
		$sql .= "        ".$DetalleGlosa['valor_no_aceptado'].", ";
		$sql .= "        '".$DetalleGlosa['observacion']."' ";
		$sql .= "       ); ";

		$sql .= "		UPDATE esm_glosa_factura ";
		$sql .= "		SET ";
		$sql .= "		auditor_id =".UserGetUID().", ";
		$sql .= "		fecha_cierre = NOW(), ";
		$sql .= "		sw_estado = '3' ";
		$sql .= "		WHERE ";
		$sql .= "				esm_glosa_id = ".$DetalleGlosa['esm_glosa_id']." ";
		$sql .= "		AND		sw_estado = '1'; ";
		
		$sql .= "		UPDATE fac_facturas ";
		$sql .= "		SET ";
		$sql .= "		saldo = (saldo ".$signo." ".$DetalleGlosa['valor_aceptado'].") ";
		$sql .= "		WHERE ";
		$sql .= "				empresa_id = '".$DetalleGlosa['empresa_id_factura']."' ";
		$sql .= "		AND		prefijo = '".$DetalleGlosa['prefijo']."' ";
		$sql .= "		AND		factura_fiscal = ".$DetalleGlosa['factura_fiscal']."; ";
		
		$sql .= "UPDATE documentos ";
		$sql .= "       SET ";
		$sql .= "       numeracion = numeracion + 1 ";
		$sql .= " where ";
		$sql .= "        empresa_id = '".$DocumentoNota['empresa_id']."' ";
		$sql .= " and    documento_id = ".$DocumentoNota['documento_id'].";";
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
	
	}
	
?>