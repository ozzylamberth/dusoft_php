<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasEstadosDocumentos.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio(){}
	
  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function GuardarDaticos($datos)
	{
	  $sql  = "INSERT INTO inv_terceros_proveedores_correosadicionales (";
    $sql .= "       tercero_id     , ";
	  $sql .= "       email      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['tercero_id']."',";
	    $sql .= "        '".$datos['email']."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
    
    	function GuardarDaticos2($datos)
	{
	  $sql  = "INSERT INTO inv_terceros_proveedores_politicasdevolucion (";
    $sql .= "       tercero_id     , ";
	  $sql .= "       descripcion      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['tercero_id']."',";
	    $sql .= "        '".$datos['descripcion']."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	
/**************************************************************************************
		* Busca si existe una Molécula con el código enviado desde formulario usuario
		* 
		* @return array
		***************************************************************************************/
		function BuscarTerceroProveedor($TerceroId)
		{
			//$this->debug=true;
    	$sql = "SELECT

                a.tipo_id_tercero,

                a.tercero_id,

                tp.pais || '-' || 

                td.departamento || '-' ||

                tm.municipio as Ubicacion,

                a.direccion,

                a.telefono,

                a.fax,

                a.email,

                a.celular,

                a.nombre_tercero,

                a.dv,

                b.codigo_proveedor_id,

               b.representante_ventas,

                b.telefono_representante_ventas,

                b.nombre_gerente,

                b.telefono_gerente

                         

                FROM

                terceros AS a,

                terceros_proveedores AS b,

               tipo_pais AS tp,

                tipo_dptos as td,

                tipo_mpios as tm

                where

                a.tercero_id = '".$TerceroId."'

                and

                a.tercero_id= b.tercero_id

                and

                a.tipo_mpio_id = tm.tipo_mpio_id

                and

                a.tipo_dpto_id = tm.tipo_dpto_id

                and

                a.tipo_pais_id = tm.tipo_pais_id

                and

                tm.tipo_dpto_id = td.tipo_dpto_id

                and

                td.tipo_pais_id = tp.tipo_pais_id

                and

                b.estado = '1'
                ;";
						
			
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

	
      function GuardarDaticos3($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE terceros ";
    $sql .= "SET email = '".$datos['email']."'";
	  $sql .= " Where ";
    $sql .= "tercero_id ='".$datos['tercero_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
  function GuardarDaticos4($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE terceros_proveedores ";
    $sql .= "SET telefono_representante_ventas = '".$datos['telefono']."'";
	  $sql .= " Where ";
    $sql .= "tercero_id ='".$datos['tercero_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
  function GuardarDaticos5($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE terceros_proveedores ";
    $sql .= "SET representante_ventas = '".$datos['representante_ventas']."'";
	  $sql .= " Where ";
    $sql .= "tercero_id ='".$datos['tercero_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
    function Listar_CorreosAdicionales($Tercero_Id)
		{
			//$this->debug=true;
      $sql = "SELECT 
                    tercero_proveedor_correoadicional as codigo,
                    email
                    FROM 
                    inv_terceros_proveedores_correosadicionales
                    where
                    tercero_id='".$Tercero_Id."'
                    order By tercero_proveedor_correoadicional;";
       
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
	
  
  function Listar_PoliticasDevolucion($Tercero_Id)
		{
			//$this->debug=true;
      $sql = "SELECT 
                    tercero_proveedor_politicadevolucion_id as codigo,
                    descripcion
                    FROM 
                    inv_terceros_proveedores_politicasdevolucion
                    where
                    tercero_id='".$Tercero_Id."'
                    order By tercero_proveedor_politicadevolucion_id;";
       
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
  
  function Listar_TercerosProveedores($empresa_id,$tercero_id,$nombre,$offset)
		{
    //$this->debug=true;
				$sql = "SELECT
                a.tipo_id_tercero,
                a.tercero_id,
                tp.pais || '-' || 
                td.departamento || '-' ||
                tm.municipio as Ubicacion,
                a.direccion,
                a.telefono,
                a.fax,
                a.email,
                a.celular,
                a.nombre_tercero,
                a.dv,
                b.codigo_proveedor_id,
                c.descripcion,
                b.representante_ventas,
                b.telefono_representante_ventas,
                b.nombre_gerente,
                b.telefono_gerente
                         
                FROM
                terceros AS a,
                terceros_proveedores AS b,
                actividades_industriales AS c,
                tipo_pais AS tp,
                tipo_dptos as td,
                tipo_mpios as tm
                where
                a.tercero_id= b.tercero_id
                and
                b.actividad_id=c.actividad_id
                and
                a.tipo_mpio_id = tm.tipo_mpio_id
                and
                a.tipo_dpto_id = tm.tipo_dpto_id
                and
                a.tipo_pais_id = tm.tipo_pais_id
                and
                tm.tipo_dpto_id = td.tipo_dpto_id
                and
                td.tipo_pais_id = tp.tipo_pais_id
                and
                a.empresa_id = '".$empresa_id."'
                and b.estado = '1'
                and a.nombre_tercero ILIKE '%".$nombre."%'
                and a.tercero_id ILIKE '%".$tercero_id."%'
                ";
                
                
                 if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY b.tercero_id, a.nombre_tercero ASC";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

			//print_r($sql);			
            
      
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