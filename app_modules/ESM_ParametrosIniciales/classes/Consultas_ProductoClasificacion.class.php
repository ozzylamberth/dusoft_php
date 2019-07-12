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
  
  
  
  class Consultas_ProductoClasificacion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_ProductoClasificacion(){}
	
  	/**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Obtener_Empresas()
		{
		$sql = "SELECT 
                    tipo_id_tercero||' - '|| id as identificacion,
                    empresa_id,
                    razon_social
                    FROM 
                    empresas ";
      $sql .= " where ";
      $sql .= "       sw_activa = '1' ";
      

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
    
    /**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Obtener_ClasificacionesProductos()
		{
		$sql = "SELECT 
                    *
                    FROM 
                    esm_clasificaciones_producto ";
      $sql .= " where ";
      $sql .= "       sw_estado = '1' ";
      

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
    
    
    /**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Consultar_ClasificacionProducto($empresa_id,$codigo_producto)
		{
    //$this->debug=true;
		$sql = "SELECT 
                    *
                    FROM 
                    esm_clasificacion_producto_empresa ";
      $sql .= " where ";
      $sql .= "          empresa_id = '".$empresa_id."' ";
      $sql .= "   and    codigo_producto = '".$codigo_producto."' ";
      

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

  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
		function Insertar_ClasificacionProducto($codigo_producto,$tipo_clasificacion_id,$empresa_id)
		{
		    
    $sql  = "INSERT INTO esm_clasificacion_producto_empresa (";
		$sql .= "       codigo_producto, ";
		$sql .= "       tipo_clasificacion_id, ";
		$sql .= "       empresa_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        '".$tipo_clasificacion_id."', ";
		$sql .= "        '".$empresa_id."' ";
		$sql .= "       ); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
    
    function Modificar_ClasificacionProducto($codigo_producto,$tipo_clasificacion_id,$empresa_id)
		{
		    
    $sql  = "UPDATE esm_clasificacion_producto_empresa ";
		$sql .= "     SET  tipo_clasificacion_id = '".$tipo_clasificacion_id."' ";
		$sql .= "    ";
		$sql .= "where ";
		$sql .= "           codigo_producto ='".$codigo_producto."' ";
		$sql .= "  and      empresa_id = '".$empresa_id."' ";
		$sql .= "       ";			
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
		function Eliminar_ClasificacionProducto($codigo_producto,$tipo_clasificacion_id,$empresa_id)
		{
      //$this->debug=true;
      $sql = " delete from esm_clasificacion_producto_empresa ";
      $sql .= " where ";
      $sql .= "        codigo_producto = '".$codigo_producto."' ";
      $sql .= " and    empresa_id = '".$empresa_id."'; ";
           
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
 		function Listado_ProductosEmpresa($empresa_id,$codigo_producto,$descripcion,$offset)
		{
      // $this->debug=true;
      if(!empty($codigo_producto))
        {
        $filtro = " and  invp.codigo_producto = '".$codigo_producto."' ";
        }
      $sql .= "Select DISTINCT
                    codigo_producto,
                    tipo_clasificacion_id,
                    resultado,
                    fc_descripcion_producto_alterno(codigo_producto) as descripcion ";
      $sql .= " from ( ";
              $sql .= "SELECT 
                            inv.codigo_producto,
                            '' as tipo_clasificacion_id,
                            '1' as resultado
                            from 
                            inventarios_productos invp,
                            inventarios inv
              ";
              $sql .= " where ";
              $sql .= "        invp.descripcion ILIKE '%".$descripcion."%' ";
              $sql .= " ".$filtro;
              $sql .= " and    invp.estado = '1' ";
              $sql .= " and    invp.codigo_producto = inv.codigo_producto ";
              $sql .= " and    inv.empresa_id = '".$empresa_id."' ";
              $sql .= " and    inv.estado = '1' ";
              $sql .= " and    inv.empresa_id ||' '||inv.codigo_producto NOT IN
                                                                         (
                                                                         SELECT empresa_id ||' '||codigo_producto
                                                                         FROM esm_clasificacion_producto_empresa
                                                                         WHERE
                                                                              empresa_id = '".$empresa_id."'
                                                                         AND  codigo_producto = inv.codigo_producto
                                                                         )";
              $sql .= " UNION  ";
              $sql .= "SELECT 
                            inv.codigo_producto,
                            ecpe.tipo_clasificacion_id,
                            '0' as resultado
                            from 
                            inventarios_productos invp,
                            inventarios inv,
                            esm_clasificacion_producto_empresa ecpe ";
              $sql .= " where ";
              $sql .= "        invp.descripcion ILIKE '%".$descripcion."%' ";
              $sql .= " ".$filtro;
              $sql .= " and    invp.estado = '1' ";
              $sql .= " and    invp.codigo_producto = inv.codigo_producto ";
              $sql .= " and    inv.empresa_id = '".$empresa_id."' ";
              $sql .= " and    inv.estado = '1' ";
              $sql .= " and    inv.codigo_producto = ecpe.codigo_producto ";
              $sql .= " and    inv.empresa_id = ecpe.empresa_id ";
      $sql .= " ) as T ";
      //$sql .= " WHERE ";
      //$sql .= "       T.tipo_clasificacion_id <> '' ";
      
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY resultado ASC ";
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
	
	}
?>