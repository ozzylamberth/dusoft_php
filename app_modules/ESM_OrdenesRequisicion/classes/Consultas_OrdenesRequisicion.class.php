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
  
  
  
  class Consultas_OrdenesRequisicion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_OrdenesRequisicion(){}
	
  

  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
		function Insertar_ORequisicionTemporal($Formulario)
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
		}
	
   /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listado_TiposRequisicion()
		{
    		//	$this->debug=true;
    		$sql = "SELECT 
    		*
    		FROM 
              esm_tipos_ordenes_requisicion ";
    		$sql .= " where ";
    		$sql .= "        sw_estado = '1' ";
    		$sql .= " ORDER BY movimiento ";
    	

        
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
    		//	$this->debug=true;
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
		//	$this->debug=true;
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
		//	$this->debug=true;
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
			//$this->debug=true;
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
      $sql .= "     and   tmp.empresa_id_registro  = emp.empresa_id   ";
      $sql .= " ORDER BY orden_requisicion_tmp_id ";
      
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
    $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
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
		function Obtener_OrdenesRequisicion($orden_requisicion_id)
		{
			//$this->debug=true;
      $sql = "select 
              COALESCE(b.orden_requisicion_id,-1) as oaux,
              a.orden_requisicion_id,
              c.nombre_tercero,
              d.descripcion
              from 
              esm_orden_requisicion as a 
              LEFT JOIN esm_orden_requisicion_d_pacientes as b ON (a.orden_requisicion_id = b.orden_requisicion_id)
              JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero) and (a.tercero_id = c.tercero_id)
              JOIN esm_tipos_fuerzas as d ON(a.tipo_fuerza_id = d.tipo_fuerza_id)
              where a.orden_requisicion_id = ".$orden_requisicion_id."
              group by a.orden_requisicion_id,c.nombre_tercero,b.orden_requisicion_id,d.descripcion ";
      
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
      * Funcion donde se consulta  informacion completa del  Proveedor.
      * @param string $noId cadena con el valor del numero de identificacion
      * @param string $tipoId cadena con el valor del tipo de identificacion
      * @return array $datos vector con la informacion de los Proveedor 
      */
      function ConsultarListaDetalle($Formulario,$lista,$empresa_id,$offset)
      {
       //$this->debug=true;
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
                                        inventarios_productos invp ";
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
                                     and descripcion ILIKE '%".$Formulario['descripcion']."%' ";
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
                                        inventarios_productos invp ";
                      $sql .= " WHERE       lpd.codigo_lista = '".$lista['codigo_lista']."' 
                                      and   lpd.empresa_id = '".$empresa_id."'
                                      and   lpd.codigo_producto = invp.codigo_producto 
                                      and   invp.descripcion ILIKE '%".$Formulario['descripcion']."%'
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
    		//	$this->debug=true;
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
		//$this->debug=true;
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
		//$this->debug=true;
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
			//$this->debug=true;
      $sql = " delete from esm_orden_requisicion_tmp_d ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id." ";
      $sql .= " and    codigo_producto = '".$codigo_producto."' ";
      
			//$this->debug=true;
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
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
    
    function BorrarTemporal($orden_requisicion_tmp_id,$codigo_producto)
		{
			//$this->debug=true;
      $sql = " delete from esm_orden_requisicion_tmp ";
      $sql .= " where ";
      $sql .= "        orden_requisicion_tmp_id = ".$orden_requisicion_tmp_id."; ";
         
			//$this->debug=true;
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
    		//$this->debug=true;
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
    		//$this->debug=true;
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
	
	
	 Function Consultar_Registros_tmp_suministro_pac($orden_id)
	 {
	// $this->debug=true;
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
	
	
	}
	
?>