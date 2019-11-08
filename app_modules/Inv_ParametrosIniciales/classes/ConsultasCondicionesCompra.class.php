<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasCondicionesCompra.class.php,
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



 class ConsultasCondicionesCompra extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasCondicionesCompra(){}

  


  
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarCondicionCompra($CodigoCondicion)
{

          $sql="
            select
            condicion_compra_id as codigo,
            descripcion,
            estado
            From
            inv_condiciones_compra
            where
            condicion_compra_id = '".$CodigoCondicion."';";
 
 //$this->debug=true;
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
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function ListadoCondicionesCompra($offset)
{
        //$this->debug=true;
          $sql="
            select
            condicion_compra_id as codigo,
            descripcion,
            estado
            From
            inv_condiciones_compra
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "ORDER BY estado DESC,condicion_compra_id ";
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
		* Insertar una SubClase a Una Clase
		* 
		* @return token
		************************************************************************************/
		
	function InsertarCondicionCompra($Datos)
	{
	  
  //  $this->debug=true;
    $sql  = "INSERT INTO inv_condiciones_compra (";
    $sql .= "       condicion_compra_id     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['condicion_compra_id']."',";
	  $sql .= "        '".$Datos['descripcion']."',";
	  $sql .= "        '".$Datos['estado']."'";
	  $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  

/*
*Funcion para Modificar Grupos
*/

    function ModificarCondicionesCompras($datos)
	{
    //$this->debug=true;
	  $sql  = "UPDATE inv_condiciones_compra ";
    $sql .= "SET ";
    $sql .= "descripcion = '".$datos['descripcion']."',";
    $sql .= "condicion_compra_id = '".$datos['condicion_compra_id']."'";
	  $sql .= " Where ";
    $sql .= "condicion_compra_id ='".$datos['condicion_compra_id_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	}
	
?>