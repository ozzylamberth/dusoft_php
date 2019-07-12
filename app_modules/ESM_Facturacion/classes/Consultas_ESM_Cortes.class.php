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
  
  
  
  class Consultas_ESM_Cortes extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_ESM_Cortes(){}
 
   
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Obtener_TrasladosESM($empresa_id,$fecha_inicio,$fecha_final)
		{
        $filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    
    		$sql = "
      
                     select dc.empresa_id,
							dc.prefijo,
							dc.numero 	
				
						FROM inv_bodegas_movimiento_traslados_esm as dc, 
						inv_bodegas_movimiento as d
				
                   WHERE
						 dc.empresa_id = d.empresa_id 
						and dc.prefijo = d.prefijo 
						and dc.numero = d.numero 
						and dc.sw_corte = '0' 
                              ".$filtro."
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
		function Obtener_DespachosESM($empresa_id,$fecha_inicio,$fecha_final)
		{
    		$filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
      
    		$sql = "select      dc.empresa_id,
							dc.prefijo,
							dc.numero
							
							FROM inv_bodegas_movimiento_despacho_campania as dc, 
							inv_bodegas_movimiento as d
							
							WHERE dc.empresa_id = d.empresa_id 
							and dc.prefijo = d.prefijo
							and dc.numero = d.numero 
							and dc.sw_corte = '0' 
                                   ".$filtro."
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
		function Obtener_DispensacionESM($empresa_id,$fecha_inicio,$fecha_final)
		{
	    $filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
	    $filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    
    		$sql = "	select 	esm_formulacion_despacho_id
						FROM   esm_formulacion_despachos_medicamentos as dc,
							   bodegas_documentos as d
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and dc.sw_corte = '0' 
            ".$filtro."
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
		function Obtener_DispensacionPendientesESM($empresa_id,$fecha_inicio,$fecha_final)
		{
    		$filtro = "  AND       d.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
			$filtro .= " AND       d.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		
    		$sql = "
              select dc.bodegas_doc_id,
					      dc.numeracion
				
					FROM esm_formulacion_despachos_medicamentos_pendientes as dc,
					bodegas_documentos as d
				
					WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
					and dc.numeracion = d.numeracion 
					and dc.sw_corte = '0' 
                   ".$filtro."
                   
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
		function ObtenerContratoId()
		{
		
      $sql = "SELECT 
                    pc.plan_id,
                    pl.*,
                    lp.codigo_lista
                    FROM 
                    esm_parametros_contrato pc,
                    planes pl,
                    listas_precios lp,
                    listas_precios_detalle lpd
                    ";
      $sql .= " where ";
      $sql .= "           pc.empresa_id IS NULL ";
      $sql .= "      and  pc.sw_estado = '1' ";
      $sql .= "      and  pc.plan_id = pl.plan_id ";
      $sql .= "      and  pl.estado = '1' ";
      $sql .= "      and  pl.lista_precios = lp.codigo_lista ";
      $sql .= "      and  lp.codigo_lista = lpd.codigo_lista ";
           
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
		
      $sql = "select codigo_producto,
              sum(cantidad) as total_cantidad,
              sum(valor) as valor_total,
             (sum(valor)/sum(cantidad)) as valor_unitario
        FROM
          (
                   select
                   codigo_producto,
                   cantidad,
                   valor,
                   porcentaje_intermediacion
                   FROM
                       esm_facturacion_temporal_traslados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                  UNION
                   select
                   codigo_producto,
                   cantidad,
                   valor,
                   porcentaje_intermediacion
                   FROM
                       esm_facturacion_temporal_despachados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                   UNION
                   select
                   codigo_producto,
                   cantidad,
                   valor,
                   porcentaje_intermediacion
                   FROM
                       esm_facturacion_temporal_dispensados
                   WHERE
                        empresa_id = '".$empresa_id."'
                   AND  documento_id = ".$documento_id."
                   UNION
                   select
                   codigo_producto,
                   cantidad,
                   valor,
                   porcentaje_intermediacion
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
		function Buscar_CabeceraTemporal($empresa_id)
		{
	
      $sql = "SELECT  * From 
                   esm_corte_temporal eft ";
      $sql .= " where ";
      $sql .= "          eft.empresa_id = '".$empresa_id."' ";
    
                 
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
    *	FUNCION CONSULTAR TEMPORALES DE TRASLADOS QUE ESTAN EN UN CORTE TEMPORAL 
    */
		function Buscar_DetalleTemporal_traslado($empresa_id,$corte_id)
		{
		
		$sql = " SELECT  doc.descripcion as documento_descripcion,
		                 tmp.*,
						 emp.razon_social
		
		
		         FROM    esm_corte_traslados_temporal as tmp,
					     inv_bodegas_movimiento_traslados_esm  as dc,
						 inv_bodegas_movimiento as d,
						 inv_bodegas_documentos  as db,
						 documentos doc,
						 empresas emp
				 WHERE    tmp.empresa_tras_id=dc.empresa_id
				 and      tmp.prefijo=dc.prefijo
				 and      tmp.numero=dc.numero
				 and      dc.empresa_id = d.empresa_id 
				 and      dc.prefijo = d.prefijo 
				 and      dc.numero = d.numero 
				 and      d.documento_id=db.documento_id
                 and      db.documento_id=doc.documento_id
				 and      doc.empresa_id=emp.empresa_id
				 and      tmp.empresa_id = '".$empresa_id."' 
				 and      tmp.corte_tmp_id = ".$corte_id."
                 and      dc.sw_corte = '0'				 ";
				  
    
		 
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
    *	FUNCION CONSULTAR TEMPORALES DE CAMPANIA  QUE ESTAN EN UN CORTE TEMPORAL 
    */
		function Buscar_DetalleTemporal_campania($empresa_id,$corte_id)
		{
		
			$sql ="  SELECT  doc.descripcion as documento_descripcion,
		                 tmp.*,
						 emp.razon_social
		
		
		         FROM    esm_corte_despacho_campania_temporal as tmp,
					     inv_bodegas_movimiento_despacho_campania  as dc,
						 inv_bodegas_movimiento as d,
						 inv_bodegas_documentos  as db,
						 documentos doc,
						 empresas emp
				 WHERE    tmp.empresa_des_id=dc.empresa_id
				 and      tmp.prefijo=dc.prefijo
				 and      tmp.numero=dc.numero
				 and      dc.empresa_id = d.empresa_id 
				 and      dc.prefijo = d.prefijo 
				 and      dc.numero = d.numero 
				 and      d.documento_id=db.documento_id
                 and      db.documento_id=doc.documento_id
				 and      doc.empresa_id=emp.empresa_id
				 and      tmp.empresa_id = '".$empresa_id."' 
				 and      tmp.corte_tmp_id = ".$corte_id."
                 and      dc.sw_corte = '0'				 ";
			
			   
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
    *	FUNCION CONSULTAR TEMPORALES DE DISPENSACION   QUE ESTAN EN UN CORTE TEMPORAL 
    */
		function Buscar_DetalleTemporal_Dispensados($empresa_id,$corte_id)
		{
		
			$sql ="  SELECT  db.descripcion as documento_descripcion,
			               	db.prefijo,
		                 tmp.*,
						 emp.razon_social,
						 dc.bodegas_doc_id,
						 dc.numeracion,
						 ext.formula_papel,
						 d.total_costo
						 
		
		
		         FROM    esm_corte_despacho_medicamentos_temporal as tmp,
					     esm_formulacion_despachos_medicamentos  as dc,
						 bodegas_documentos as d,
						 bodegas_doc_numeraciones  as db,
						 bodegas bod,
						 centros_utilidad cent,
						 empresas emp,
						 esm_formula_externa ext
						
				 WHERE    tmp.esm_formulacion_despacho_id=dc.esm_formulacion_despacho_id 	
				 and      dc.bodegas_doc_id = d.bodegas_doc_id 
				 and      dc.numeracion = d.numeracion 
				 and      d.bodegas_doc_id=db.bodegas_doc_id
				 and      db.empresa_id=bod.empresa_id
				 and      db.centro_utilidad=bod.centro_utilidad
				 and      db.bodega=bod.bodega
				 and      bod.empresa_id=cent.empresa_id
				 and      bod.centro_utilidad=cent.centro_utilidad
				 and      cent.empresa_id=emp.empresa_id
			     and      dc.formula_id=ext.formula_id			 
                 and      tmp.empresa_id = '".$empresa_id."' 
				 and      tmp.corte_tmp_id = ".$corte_id."
                 and      dc.sw_corte = '0'				 ";
			
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
    *	FUNCION CONSULTAR TEMPORALES DE DISPENSACION PENDIENTES DESPACHADOS  QUE ESTAN EN UN CORTE TEMPORAL 
    */
		function Buscar_DetalleTemporal_Dispensados_Pendientes($empresa_id,$corte_id)
		{
			
			$sql ="  SELECT  db.descripcion as documento_descripcion,
		                 tmp.*,
						  emp.razon_social,
						   dc.bodegas_doc_id,
						 dc.numeracion,
						 ext.formula_papel,
						 d.total_costo,
						 	db.prefijo
		
		
		         FROM    esm_corte_despacho_medicamentos_pendientes_temporal as tmp,
					     esm_formulacion_despachos_medicamentos_pendientes  as dc,
						 bodegas_documentos as d,
						 bodegas_doc_numeraciones  as db,
						 bodegas bod,
						 centros_utilidad cent,
						 empresas emp,
						 esm_formula_externa ext
						
				 WHERE    tmp.bodegas_doc_id=dc.bodegas_doc_id 	
				 and     tmp.numeracion=dc.numeracion
				 and      dc.bodegas_doc_id = d.bodegas_doc_id 
				 and      dc.numeracion = d.numeracion 
				 and      d.bodegas_doc_id=db.bodegas_doc_id
				  and      db.empresa_id=bod.empresa_id
				 and      db.centro_utilidad=bod.centro_utilidad
				 and      db.bodega=bod.bodega
				 and      bod.empresa_id=cent.empresa_id
				 and      bod.centro_utilidad=cent.centro_utilidad
				 and      cent.empresa_id=emp.empresa_id
                 and      tmp.empresa_id = '".$empresa_id."' 
				 and      tmp.corte_tmp_id = ".$corte_id."
				      and      dc.formula_id=ext.formula_id		
                 and      dc.sw_corte = '0'				 ";
			
						
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
		
		
		
		
		
		
		
		
  
  
	
  
  function Insertar_CabeceraTemporal($request,$fecha_inicio,$fecha_fin)
	{
	     
		$sql  = "INSERT INTO esm_corte_temporal (";
		$sql .= "       corte_tmp_id, ";
		$sql .= "       fecha_final, ";
		$sql .= "       fecha_inicio, ";
		$sql .= "       empresa_id, ";
		$sql .= "       usuario_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        DEFAULT, ";
		$sql .= "        '".$fecha_fin."', ";
		$sql .= "        '".$fecha_inicio."', ";
		$sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "        ".UserGetUID()." ";
	
		$sql .= "       )RETURNING(corte_tmp_id); ";			
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
		

    
    function Insertar_DetalleTemporal_traslado($request,$corte_tmp_id,$empresa_tras_id,$prefijo,$numero)
		{
			
			$sql  = "INSERT INTO  esm_corte_traslados_temporal(";
			$sql .= "       empresa_id, ";
			$sql .= "       corte_tmp_id, ";
			$sql .= "       empresa_tras_id, ";
			$sql .= "       prefijo, ";
			$sql .= "       numero ";
		
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$request['datos']['empresa_id']."', ";
			$sql .= "        ".$corte_tmp_id.", ";
			$sql .= "        '".$empresa_tras_id."', ";
			$sql .= "        '".$prefijo."', ";
			$sql .= "        ".$numero." ";
			$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$rst->Close();
		return true;
		}
    
 /* INSERTAR TEMPORAL PARA  CAMPANIA */
 
 
   function Insertar_DetalleTemporal_campania($request,$corte_tmp_id,$empresa_des_id,$prefijo,$numero)
	{
			
			$sql  = "INSERT INTO  esm_corte_despacho_campania_temporal(";
			$sql .= "       empresa_id, ";
			$sql .= "       corte_tmp_id, ";
			$sql .= "       empresa_des_id, ";
			$sql .= "       prefijo, ";
			$sql .= "       numero ";
		
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$request['datos']['empresa_id']."', ";
			$sql .= "        ".$corte_tmp_id.", ";
			$sql .= "        '".$empresa_des_id."', ";
			$sql .= "        '".$prefijo."', ";
			$sql .= "        ".$numero." ";
			$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
	}
 /* INSERTAR DESPACHOS dispensacion sin pendientes  */
 
	function Insertar_DetalleTemporal_dispensacion($request,$corte_tmp_id,$esm_formulacion_despacho_id)
	{
		
			$sql  = "INSERT INTO  esm_corte_despacho_medicamentos_temporal(";
			$sql .= "       empresa_id, ";
			$sql .= "       corte_tmp_id, ";
			$sql .= "       esm_formulacion_despacho_id ";
				
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$request['datos']['empresa_id']."', ";
			$sql .= "        ".$corte_tmp_id.", ";
			$sql .= "        ".$esm_formulacion_despacho_id." ";
			
			$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
	}
	
	
	 /* INSERTAR DESPACHOS dispensacion  pendientes  */
 
	function Insertar_DetalleTemporal_dispensacion_pendientes($request,$corte_tmp_id,$bodegas_doc_id,$numeracion)
	{
		
			$sql  = "INSERT INTO  esm_corte_despacho_medicamentos_pendientes_temporal(";
			$sql .= "       empresa_id, ";
			$sql .= "       corte_tmp_id, ";
			$sql .= "       bodegas_doc_id, ";
			$sql .= "       numeracion ";
				
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$request['datos']['empresa_id']."', ";
			$sql .= "        ".$corte_tmp_id.", ";
			$sql .= "        ".$bodegas_doc_id.", ";
			$sql .= "        ".$numeracion." ";
			
			$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
	}
    

 function Insertar_Corte($request,$DATOS)
		{
	  
    $sql  = "INSERT INTO esm_corte (";
		$sql .= "       corte_id, ";
		$sql .= "       fecha_inicio, ";
		$sql .= "       fecha_final, ";
		$sql .= "       empresa_id, ";
		$sql .= "       usuario_id ";
		
    $sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$DATOS['fecha_inicio']."', ";
		$sql .= "        '".$DATOS['fecha_final']."', ";
    $sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "         ".UserGetUID()." ";
	  $sql .= "       )RETURNING(corte_id); ";	
	
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
    
   function Insertar_DetalleCorte($valor,$Token,$tabla,$campo)
		{
	
    $sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       ".$campo.", ";
		$sql .= "       ems_corte_id, ";
		$sql .= "       formula_papel, ";
		$sql .= "       documento, ";
		$sql .= "       valor, ";
		$sql .= "       descripcion ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        ".$Token['corte_id'].", ";
		$sql .= "        '".$valor['formula_papel']."', ";
    $sql .= "        '".$valor['prefijo']."-".$valor['numeracion']."', ";
		$sql .= "        COALESCE(".$valor['total_costo'].",0), ";
		$sql .= "        '".$valor['documento_descripcion']."' ";
		$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
        

  
    function Estado_DESPACHOS_TRASLADOS($empresa_id,$fecha_inicio,$fecha_final,$documento,$tabla)
		{
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		
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
         
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
    function Estado_DISPENSADOS($empresa_id,$fecha_inicio,$fecha_final,$Token,$tabla)
		{
        //esm_formulacion_despachos_medicamentos
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    	
    		$sql = "
                   UPDATE 
                       ".$tabla." a
                       SET
                       sw_corte = '1',
                       esm_corte_id = ".$Token['corte_id']."
                       FROM
                       bodegas_documentos as b
                       WHERE
                              a.sw_corte ='0'
                       and    a.bodegas_doc_id = b.bodegas_doc_id       
                       and    a.numeracion = b.numeracion       
                       ".$filtro." ";
         
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
    function Borrar_Temporal($empresa_id)
		{
	
      $sql = " delete from esm_corte_temporal ";
      $sql .= " where ";
      $sql .= "         empresa_id = '".$empresa_id."' ";
	  
	  
	  
         
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
		/* BUSCAR CORTE PARA DESCARGAR */
		
			function Buscar_Cabecera_real($empresa_id,$filtro)
		{
				
				
				$sql = "  SELECT  	ESM.corte_id,
									ESM.fecha_inicio,
									ESM.fecha_final,
									ESM.empresa_id,
									EMP.razon_social
				 FROM   esm_corte ESM,
				       empresas EMP 
				 WHERE   ESM.corte_id = '".$filtro['no_corte']."' 
				 AND     ESM.empresa_id = '".$empresa_id."' 
				 AND    ESM.empresa_id=EMP.empresa_id ";
       
	        
                 
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
    


		function Informacion_cortes_Final($corte)
		{
			
				
				$sql = "      SELECT   	formula_papel,
								         documento,
										 valor,
										 descripcion
								FROM (

										SELECT  formula_papel,
												documento,
												valor,
												descripcion
										
										FROM    esm_corte_dispensacion
										WHERE   ems_corte_id = '".$corte."'

										UNION

										SELECT 	formula_papel,
												documento,
												valor,
												descripcion
												
										FROM    esm_corte_dispensacion_pendientes
										WHERE    ems_corte_id = '".$corte."'
									)AS A  ";
 
	        
                 
							if(!$rst = $this->ConexionBaseDatos($sql))	return false;
							$rst->Close();
              return $rst;
		}
    
	
	}
	
?>