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
  
  
  
  class Consultas_OrdenesSuministro extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_OrdenesSuministro(){}
	
  

  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
		function Insertar_OSuministroTemporal($Formulario)
		{
		list($tipo_orden_requisicion,$movimiento) = explode("@",$Formulario['tipo_orden_requisicion']);
		list($tipo_id_tercero,$tercero_id) = explode("@",$Formulario['esm']);
    if($movimiento=="T")
      {
	      $campos  = " empresa_id, ";
	      $campos .= " centro_utilidad, ";
	      $campos .= " bodega, ";
	      $valores = " '".$Formulario['datos']['empresa_id']."', ";
	      $valores .= " '".$Formulario['centro_utilidad']."', ";
	      $valores .= " '".$Formulario['bodega']."',";
      }
        $sql  = "INSERT INTO esm_orden_requisicion_tmp (";
		$sql .= "       orden_requisicion_tmp_id, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       tipo_fuerza_id, ";
		$sql .= "       tipo_orden_requisicion, ";
		$sql .= "       observacion, ";
		$sql .= "       empresa_id_registro, ";
		$sql .= "       ".$campos;
        $sql .= "       usuario_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$tipo_id_tercero."', ";
		$sql .= "        '".$tercero_id."', ";
		$sql .= "        ".$Formulario['tipo_fuerza_id'].", ";
		$sql .= "        ".$tipo_orden_requisicion.", ";
		$sql .= "        '".$Formulario['observacion']."', ";
		$sql .= "        '".$Formulario['datos']['empresa_id']."', ";
		$sql .= "       ".$valores;
        $sql .= "        ".UserGetUID()." ";
		$sql .= "       )RETURNING(orden_requisicion_tmp_id); ";			
		
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
		}
	
   /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listado_TiposRequisicion()
		{
    	
    		$sql = "SELECT 
    		*
    		FROM 
              esm_tipos_ordenes_requisicion ";
    		$sql .= " where ";
    		$sql .= "        sw_estado = '1' and 	movimiento='T'  ";
    		$sql .= " ORDER BY tipo_orden_requisicion ";
    	

        
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
		function Listado_CentrosUtilidad($empresa_id)
		{
    		
    		$sql = "SELECT 
    		*
    		FROM 
                  centros_utilidad ";
    		$sql .= " where ";
    		$sql .= "        empresa_id = '".$empresa_id."' ";
       
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
		function Listar_TiposFuerzas()
		{
	
      $sql = "SELECT 
                    *
                    FROM 
                    esm_tipos_fuerzas ";
      $sql .= " where ";
      $sql .= "      sw_activo = '1' ";
    
    $sql .= " ORDER BY tipo_fuerza_id ";
    
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
		function Listar_ESM()
		{
		
      $sql = "SELECT 
                    t.tipo_id_tercero||' - '|| t.tercero_id as identificacion,
                    t.*,
                    tp.pais ||'-'||td.departamento ||'-'||tm.municipio as ubicacion
                    FROM 
                    terceros t,
                    tipo_mpios tm,
                    tipo_dptos td,
                    tipo_pais tp,
                    esm_empresas esm ";
      $sql .= " where ";
      $sql .= "        t.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= " and    t.tercero_id = esm.tercero_id ";
      $sql .= " and    t.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= " and    t.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= " and    t.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= " and    tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= " and    tm.tipo_pais_id = td.tipo_pais_id ";
      $sql .= " and    td.tipo_pais_id = tp.tipo_pais_id ";
      
 /*     
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
*/   
       
    $sql .= " ORDER BY t.nombre_tercero ASC ";
//      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

    
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
		function Obtener_InfoDocTemporal($orden_requisicion_tmp_id,$empresa_id)
		{
		
      $sql = "SELECT 
                     tr.descripcion_orden_requisicion,
                     tf.descripcion as tipo_fuerza,
                     esm.nombre_tercero,
                     tmp.*,
                     usu.nombre
            FROM        
                     esm_orden_requisicion_tmp tmp,
                     terceros esm,
                     esm_tipos_fuerzas tf,
                     esm_tipos_ordenes_requisicion tr,
                     system_usuarios usu
                    ";
      $sql .= " where ";
      $sql .= "           tmp.empresa_id_registro = '".$empresa_id."' ";
      $sql .= "     and   tmp.orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id." ";
      $sql .= "     and   tmp.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= "     and   tmp.tercero_id = esm.tercero_id ";
      $sql .= "     and   tmp.tipo_fuerza_id = tf.tipo_fuerza_id ";
      $sql .= "     and   tmp.tipo_orden_requisicion = tr.tipo_orden_requisicion ";
      $sql .= "     and   tmp.usuario_id = usu.usuario_id ";
    
    
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
		function Obtener_DocsTemporales($offset)
		{
	
      $sql = "SELECT 
                     tr.descripcion_orden_requisicion,
                     tf.descripcion as tipo_fuerza,
                     esm.nombre_tercero,
                     tmp.*,
                     usu.nombre,
                     emp.razon_social
            FROM        
                     esm_orden_requisicion_tmp tmp,
                     terceros esm,
                     esm_tipos_fuerzas tf,
                     esm_tipos_ordenes_requisicion tr,
                     system_usuarios usu,
                     empresas emp
                    ";
      $sql .= " where ";
      $sql .= "           tmp.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= "     and   tmp.tercero_id = esm.tercero_id ";
      $sql .= "     and   tmp.tipo_fuerza_id = tf.tipo_fuerza_id ";
      $sql .= "     and   tmp.tipo_orden_requisicion = tr.tipo_orden_requisicion ";
      $sql .= "     and   tmp.usuario_id = usu.usuario_id ";
      $sql .= "     and   tmp.empresa_id_registro  = emp.empresa_id ";
      $sql .= " ORDER BY orden_requisicion_tmp_id ";
      
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
    //$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
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
      * Funcion donde se consulta  informacion completa del  Proveedor.
      * @param string $noId cadena con el valor del numero de identificacion
      * @param string $tipoId cadena con el valor del tipo de identificacion
      * @return array $datos vector con la informacion de los Proveedor 
      */
      function ConsultarListaDetalle($Formulario,$lista,$empresa_id,$offset)
      {
      
        $sql .= " SELECT 
                         fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                         codigo_producto,
                         resultado,
                         porcentaje,
                         precio,
                         sw_porcentaje,
                         valor_inicial, 
                         porc_iva ";
        $sql .= " from (";
                      $sql .= "SELECT 
                                        inv.codigo_producto,
                                        '0' as resultado,
                                        '0' as porcentaje,
                                        inv.costo as precio,
                                        '0' as sw_porcentaje,
                                        inv.costo as valor_inicial, 
                                        invp.porc_iva
                                        ";
                                        
                      $sql .= " FROM     
                                        inventarios inv,
                                        inventarios_productos invp,
										medicamentos med,
										inv_med_cod_principios_activos pr";
                      $sql .= " WHERE    
                                           inv.empresa_id = '".$empresa_id."'
                                     and   inv.codigo_producto NOT IN (
                                                                  select codigo_producto
                                                                         from
                                                                         listas_precios_detalle
                                                                         where
                                                                              codigo_lista = '".$lista['codigo_lista']."'
                                                                         and  empresa_id = '".$empresa_id."'
                                                                  ) 
                                     and inv.codigo_producto = invp.codigo_producto 
									 and inv.codigo_producto=med.codigo_medicamento
									 and med.cod_principio_activo=pr.cod_principio_activo
									 and   invp.codigo_producto ILIKE '%".$Formulario['codigo']."%'
                                     and invp.descripcion ILIKE '%".$Formulario['descripcion']."%'
                                     and  pr.descripcion	ILIKE '%".$Formulario['activo_p']."%'						 ";
                      $sql .= " UNION ";                      
                      $sql .= " SELECT 
                                        lpd.codigo_producto,
                                        '1' as resultado,
                                        lpd.porcentaje,
                                        lpd.precio,
                                        lpd.sw_porcentaje,
                                        lpd.valor_inicial,
                                        invp.porc_iva
                                        ";
                      $sql .= " FROM   
                                        listas_precios_detalle lpd,
                                        inventarios_productos invp, 
										medicamentos med,
										inv_med_cod_principios_activos pr";
										
                      $sql .= " WHERE       lpd.codigo_lista = '".$lista['codigo_lista']."' 
                                      and   lpd.empresa_id = '".$empresa_id."'
                                      and   lpd.codigo_producto = invp.codigo_producto 
									  and invp.codigo_producto=med.codigo_medicamento
									 and med.cod_principio_activo=pr.cod_principio_activo
									  and   invp.codigo_producto ILIKE '%".$Formulario['codigo']."%'
                                      and   invp.descripcion ILIKE '%".$Formulario['descripcion']."%'
									   and  pr.descripcion	ILIKE '%".$Formulario['activo_p']."%'
                                      ";
        $sql .= "       ) as T ";
        $sql .= "       WHERE ";
        $sql .= "             codigo_producto NOT IN ( ";
        $sql .= "                                      select codigo_producto ";
        $sql .= "                                      from ";
        $sql .= "                                      esm_orden_requisicion_tmp_d ";
        $sql .= "                                      where ";
        $sql .= "                                         orden_requisicion_tmp_id = ".$Formulario['orden_requisicion_tmp_id']." ";
        $sql .= "                                     ) ";
         $sql .= " ORDER BY resultado DESC ";
        
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
       
        
        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        while(!$rst->EOF)
        {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
      }
     
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Listado_ProductosTemporales($orden_requisicion_tmp_id)
		{
    	
    		$sql = "SELECT 
              fc_descripcion_producto_alterno(codigo_producto) as descripcion,
              *
    		FROM 
              esm_orden_requisicion_tmp_d ";
    		$sql .= " where ";
    		$sql .= "      orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id."  ";
    		$sql .= " ORDER BY sw_pactado ";
    	

        
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
     
     function Insertar_ProductoTemporal($orden_requisicion_tmp_id,$codigo_producto,$cantidad_solicitada,$valor,$porc_iva,$sw_pactado,$porcentaje_intermediacion)
		{
		
    $sql  = "INSERT INTO esm_orden_requisicion_tmp_d (";
		$sql .= "       orden_requisicion_tmp_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad_solicitada, ";
		$sql .= "       valor, ";
		$sql .= "       porc_iva, ";
		$sql .= "       sw_pactado, ";
		$sql .= "       porcentaje_intermediacion, ";
		$sql .= "       cantidad_solicitada_inicial ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$orden_requisicion_tmp_id.", ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        ".$cantidad_solicitada.", ";
		$sql .= "        ".$valor.", ";
		$sql .= "        ".$porc_iva.", ";
		$sql .= "        '".$sw_pactado."', ";
		$sql .= "        ".$porcentaje_intermediacion.", ";
		$sql .= "        ".$cantidad_solicitada." ";
		$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
    
    function Modificar_ProductoTemporal($orden_requisicion_tmp_id,$codigo_producto,$cantidad_solicitada)
		{
		
		$sql  = "UPDATE esm_orden_requisicion_tmp_d ";
		$sql .= "       SET ";
		$sql .= "       cantidad_solicitada = ".$cantidad_solicitada." ";
		$sql .= " where ";
		$sql .= "        orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id." ";
		$sql .= " and    codigo_producto = '".$codigo_producto."'; ";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
     
       /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Borrar_Item($orden_requisicion_tmp_id,$codigo_producto)
		{
		
      $sql = " delete from esm_orden_requisicion_tmp_d ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id." ";
      $sql .= " and    codigo_producto = '".$codigo_producto."' ";
      
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
		
		  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Borrar_Item_s($orden_requisicion_tmp_id,$codigo_producto)
		{
		
      $sql = " delete from esm_orden_requisicion_tmp_d_pacientes_tmp ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id_tmp = ".$orden_requisicion_tmp_id." ";
      $sql .= " and    codigo_producto = '".$codigo_producto."' ";
      

		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
		
		
		
		
	
	 /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Borrar_Item_suministro($orden_id,$codigo_producto,$tipo_paciente,$paciente_id)
		{
	
      $sql = " delete from esm_orden_requisicion_tmp_d_pacientes_tmp ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id_tmp = ".$orden_id." ";
      $sql .= " and    codigo_producto = '".$codigo_producto."'
                and    tipo_id_paciente='".$tipo_paciente."'
                and    paciente_id='".$paciente_id."'			";
      
	
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	
  
  function Insertar_Documento($Formulario)
		{
		if($Formulario['bodega']!="")
      {
      $campos  = " empresa_id, ";
      $campos .= " centro_utilidad, ";
      $campos .= " bodega, ";
      $valores = " '".$Formulario['empresa_id']."', ";
      $valores .= " '".$Formulario['centro_utilidad']."', ";
      $valores .= " '".$Formulario['bodega']."',";
      }
      
    $sql  = "INSERT INTO esm_orden_requisicion (";
		$sql .= "       orden_requisicion_id, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       tipo_fuerza_id, ";
		$sql .= "       tipo_orden_requisicion, ";
		$sql .= "       observacion, ";
		$sql .= "       empresa_id_registro, ";
    $sql .= "     ".$campos;
    $sql .= "       usuario_id_creador, ";
    $sql .= "       usuario_id_autorizador ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$Formulario['tipo_id_tercero']."', ";
		$sql .= "        '".$Formulario['tercero_id']."', ";
		$sql .= "        ".$Formulario['tipo_fuerza_id'].", ";
		$sql .= "        ".$Formulario['tipo_orden_requisicion'].", ";
		$sql .= "        '".$Formulario['observacion']."', ";
		$sql .= "        '".$Formulario['empresa_id_registro']."', ";
		$sql .= "       ".$valores;
    $sql .= "        ".$Formulario['usuario_id'].", ";
    $sql .= "        ".UserGetUID()." ";
		$sql .= "       )RETURNING(orden_requisicion_id); ";			
		
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
		}
  
 function Insertar_ProductoDocumento($token,$Arreglo)
		{
		
    $sql  = "INSERT INTO esm_orden_requisicion_d (";
		$sql .= "       orden_requisicion_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad_solicitada, ";
		$sql .= "       valor, ";
		$sql .= "       porc_iva, ";
		$sql .= "       sw_pactado, ";
		$sql .= "       porcentaje_intermediacion, ";
		$sql .= "       cantidad_solicitada_inicial ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$token['orden_requisicion_id'].", ";
		$sql .= "        '".$Arreglo['codigo_producto']."', ";
		$sql .= "        ".$Arreglo['cantidad_solicitada'].", ";
		$sql .= "        ".$Arreglo['valor'].", ";
		$sql .= "        ".$Arreglo['porc_iva'].", ";
		$sql .= "        '".$Arreglo['sw_pactado']."', ";
		$sql .= "        ".$Arreglo['porcentaje_intermediacion'].", ";
	    $sql .= "        ".$Arreglo['cantidad_solicitada_inicial']."  ";
		$sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
    
    function BorrarTemporal($orden_requisicion_tmp_id,$codigo_producto)
		{
	
      $sql = " delete from esm_orden_requisicion_tmp ";
      $sql .= " where ";
      $sql .= "        	orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id."; ";
	  
	  /*$sql .= " delete from esm_orden_requisicion_tmp_d_pacientes_tmp ";
      $sql .= " where ";
      $sql .= "        		orden_requisicion_tmp_id_tmp = ".$orden_requisicion_tmp_id."; ";*/
	  
         
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
    
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Listado_Bodegas($empresa_id,$centro_utilidad)
		{
    	
    		$sql = "SELECT 
              *
    		FROM 
              bodegas ";
    		$sql .= " where ";
    		$sql .= "          empresa_id = '".$empresa_id."'  ";
    		$sql .= "      and centro_utilidad = '".$centro_utilidad."'  ";
    		$sql .= "      and sw_bodega_satelite = '1'  ";

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
		function Bodega($empresa_id,$centro_utilidad,$bodega)
		{
    	
    		$sql = "SELECT 
              bod.*,
              cent.descripcion as centro
    		FROM 
              bodegas bod, 
              centros_utilidad cent ";
    		$sql .= " where ";
    		$sql .= "          bod.empresa_id = '".$empresa_id."'  ";
    		$sql .= "      and bod.centro_utilidad = '".$centro_utilidad."'  ";
    		$sql .= "      and bod.bodega = '".$bodega."'  ";
    		$sql .= "      and bod.empresa_id = cent.empresa_id  ";
    		$sql .= "      and bod.centro_utilidad = cent.centro_utilidad  ";
    		
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
  /* CONSULTAR PACIENTES QUE TIENEN ESM */
  

	function Pacientes_esm($Formulario,$orden_id,$codigo_producto,$offset)
	{
  
		
		if($Formulario['tipo_id_paciente']=='-1')
		{
		  $Formulario['tipo_id_paciente']="";
		
		}
    $filtro="";	
  if($Formulario['tipo_id_paciente']!='')
	{
	  $filtro=" and     esm.tipo_id_paciente = '".$Formulario['tipo_id_paciente']."' ";
	}  
  
	if($Formulario['identificacion']!='')
	{
	  $filtro .=" and     esm.paciente_id = '".$Formulario['identificacion']."' ";
	}
//	$this->debug=true;
	  	$sql = "	SELECT  esm.tipo_id_paciente,
									esm.paciente_id,
									DA.primer_nombre ||' ' || DA.segundo_nombre||' '|| DA.primer_apellido ||' '|| DA.segundo_apellido   AS nombre_completo
							FROM    esm_pacientes as esm,
									eps_afiliados_datos DA
							WHERE   esm.tipo_id_paciente=DA.afiliado_tipo_id	
							AND     esm.paciente_id=DA.afiliado_id
              
							".$filtro."
              
							and     DA.primer_nombre ||' ' || DA.segundo_nombre||' '|| DA.primer_apellido ||' '|| DA.segundo_apellido  ILIKE '%".$Formulario['nombre']."%'
				            and     esm.tipo_id_paciente||''||esm.paciente_id not in (  select   tipo_id_paciente||''||paciente_id
																						from    esm_orden_requisicion_tmp_d_pacientes_tmp
																						where    orden_requisicion_tmp_id_tmp='".$orden_id."'
																						and      codigo_producto='".$codigo_producto."' )
							
							ORDER BY  nombre_completo ";

				    	 /*if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
				        return false;*/
				    
				        $sql .= "LIMIT 3";
				 
				       
				        
				        if(!$rst = $this->ConexionBaseDatos($sql))
				        return false;
				        $datos = array();
				        while(!$rst->EOF)
				        {
				        $datos[] = $rst->GetRowAssoc($ToUpper);
				        $rst->MoveNext();
				        }
				        $rst->Close();
				        return $datos;
    }
  
  	function ConsultarTipoId()
		{
			$sql  = "SELECT    tipo_id_tercero, descripcion ";
			$sql .= "FROM      tipo_id_terceros ";
			$sql .= "ORDER BY  tipo_id_tercero ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  function Cantidad_ProductoTemporal($orden_id,$codigo_producto)
    {
	
      $sql  = "SELECT COALESCE(sum(cantidad),0) as total ";
      $sql .= "FROM   esm_orden_requisicion_tmp_d_pacientes_tmp ";
      $sql .= "WHERE  orden_requisicion_tmp_id_tmp 	 = ".$orden_id." ";
      $sql .= "and    codigo_producto = '".$codigo_producto."' ";
      
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
	
	/* GUARDAR PACIENTE EN LA TEMPORAL */
	
	function GuardarTemporal($orden_id,$codigo_producto,$cantidad,$tipo_paciente,$id_paciente)
     {
	
	 
       $this->ConexionTransaccion();
		
				$sql = "INSERT INTO   	esm_orden_requisicion_tmp_d_pacientes_tmp
						(
										orden_requisicion_tmp_id_tmp,
										codigo_producto,
										tipo_id_paciente, 
										paciente_id,
										cantidad
						)VALUES 
						(				
										".$orden_id.", 
										'".$codigo_producto."',
										'".$tipo_paciente."',
										'".$id_paciente."',
										".$cantidad."
						); " ;
										
									
						if(!$rst = $this->ConexionTransaccion($sql))
						return false;

						$this->Commit();
						return true;
	   
	   
	   
	   
     }
	 /* CONSULTAR SI YA SE HA INGRESADO ALGUN REGISTRO DEL PRODUCTO Y PACIENTE */
	 
	 Function Consultar_Registros_tmp_suministro($orden_id,$codigo)
	 {
		$sql = " select     tmp.tipo_id_paciente,
							tmp.paciente_id,
							tmp.cantidad,
							dat.primer_nombre ||' ' || dat.segundo_nombre||' '|| dat.primer_apellido ||' '|| dat.segundo_apellido   AS nombre_completo
				from        esm_orden_requisicion_tmp_d_pacientes_tmp tmp,
				            eps_afiliados_datos dat
				where    tmp.orden_requisicion_tmp_id_tmp='".$orden_id."'
				and      tmp.codigo_producto='".$codigo."'
				and      tmp.tipo_id_paciente=dat.afiliado_tipo_id
				and      tmp.paciente_id=dat.afiliado_id 
				order  by  nombre_completo ";
			  
	       if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	 
	 
	 
	 
	 }
	 
	 /* consultar si hay registro de la Orden de suministro para pacientes */
	  function Consultar_Registros_tmp_suministro_pac($orden_id)
	 {
	 
		$sql = " select   distinct  tmp.orden_requisicion_tmp_id_tmp 
						
				from        esm_orden_requisicion_tmp_d_pacientes_tmp tmp
				       
				where    tmp.orden_requisicion_tmp_id_tmp='".$orden_id."' ";
		
			  
	       if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	 
	 
	 
	 
	 }

	/* MODIFICAR CANTIDAD POR PACIENTE */
	
	    function Modificar_Producto_X_PAC($orden_id,$codigo_producto,$cantidad,$tipo_paciente,$paciente_id)
		{
		
		$sql  = "UPDATE esm_orden_requisicion_tmp_d_pacientes_tmp ";
		$sql .= "       SET ";
		$sql .= "       	cantidad = ".$cantidad." ";
		$sql .= " where ";
		$sql .= "        	orden_requisicion_tmp_id_tmp = ".$orden_id."  and ";
		$sql .= "           codigo_producto='".$codigo_producto."' and  tipo_id_paciente='".$tipo_paciente."' and 	paciente_id='".$paciente_id."'";
		
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	/*  CALCULAR LA SUMA TOTAL POR MEDICAMENTO PARA ACTUALIZAR LA CANTIDAD MODIFICADO */
	
	

	 function consultar_moficacion_x_prodc($orden_id,$codigo_producto)
		{
		
		$sql  = "	SELECT sum(cantidad) as total
					FROM  esm_orden_requisicion_tmp_d_pacientes_tmp
					WHERE orden_requisicion_tmp_id_tmp =".$orden_id."
					AND   codigo_producto = '".$codigo_producto."' ";
					
	
		 if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	 
	}
	/* MODIFICAR CANTIDAD POR PRODUCTO */
	
	    function Modificar_Producto_X_producto($orden_id,$codigo_producto,$cantidad)
		{
		
		$sql  = "UPDATE esm_orden_requisicion_tmp_d ";
		$sql .= "       SET ";
		$sql .= "       		cantidad_solicitada = ".$cantidad." ";
		$sql .= " where ";
		$sql .= "        			orden_requisicion_tmp_id = ".$orden_id."  and ";
		$sql .= "           codigo_producto='".$codigo_producto."' ";
		
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	/* consultar informacion temporal  DEL DOCUMENTO POR PACIENTE */
	
	 Function Consultar_Registros_tmp_suministro_por_pac($orden_id)
	 {
		$sql = " select     tmp.tipo_id_paciente,
							tmp.paciente_id,
							tmp.cantidad,
							tmp.codigo_producto,
							dat.primer_nombre ||' ' || dat.segundo_nombre||' '|| dat.primer_apellido ||' '|| dat.segundo_apellido   AS nombre_completo
				from        esm_orden_requisicion_tmp_d_pacientes_tmp tmp,
				            eps_afiliados_datos dat
				where    tmp.orden_requisicion_tmp_id_tmp='".$orden_id."'
				and      tmp.tipo_id_paciente=dat.afiliado_tipo_id
				and      tmp.paciente_id=dat.afiliado_id 
				order  by  nombre_completo ";
			  
	       if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	 
	 
	 
	 
	 }
	 
	 /* GUARDAR EN LA TABLA REAL LOS SUMINISTROS POR PACIENTES */
	 function Insertar_ProductoDocumento_D_x_paciente($token,$Arreglo)
	{
		
       $sql  = "INSERT INTO esm_orden_requisicion_d_pacientes (";
		$sql .= "       orden_requisicion_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       tipo_id_paciente, ";
		$sql .= "       paciente_id, ";
		$sql .= "       cantidad ";
	
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$token['orden_requisicion_id'].", ";
		$sql .= "        '".$Arreglo['codigo_producto']."', ";
		$sql .= "        '".$Arreglo['tipo_id_paciente']."', ";
		$sql .= "        '".$Arreglo['paciente_id']."', ";
		$sql .= "        ".$Arreglo['cantidad']." ";
	
		$sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	
	
	
	
  
  
	}
	
?>