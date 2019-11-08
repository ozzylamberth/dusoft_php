<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasTiposDispensacion.class.php,
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



 class ConsultasTiposDispensacion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasTiposDispensacion(){}

  


  
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarTipoDispensacion($CodigoTipoDispensacion)
{

          $sql="
            select
            tipo_dispensacion_id as codigo,
            descripcion,
            estado
            From
            inv_farmacias_tiposdispensacion
            where
            tipo_dispensacion_id = '".$CodigoTipoDispensacion."';";
 
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
function ListadoTiposDispensacion($offset)
{

          $sql="
            select
            tipo_dispensacion_id as codigo,
            descripcion,
            estado
            From
            inv_farmacias_tiposdispensacion
            
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "ORDER BY estado DESC,tipo_dispensacion_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
 
 
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
  
    
  
  /**********************************************************************************
		* Insertar una SubClase a Una Clase
		* 
		* @return token
		************************************************************************************/
		
	function InsertarTipoDispensacion($Datos)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_farmacias_tiposdispensacion (";
    $sql .= "       tipo_dispensacion_id     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['tipo_dispensacion_id']."',";
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

    function ModificarTipoDispensacion($datos)
	{
	  $sql  = "UPDATE inv_farmacias_tiposdispensacion ";
    $sql .= "SET ";
    $sql .= "descripcion = '".$datos['descripcion']."',";
    $sql .= "tipo_dispensacion_id = '".$datos['tipo_dispensacion_id']."'";
	  $sql .= " Where ";
    $sql .= "tipo_dispensacion_id ='".$datos['tipo_dispensacion_id_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	}
	
?>