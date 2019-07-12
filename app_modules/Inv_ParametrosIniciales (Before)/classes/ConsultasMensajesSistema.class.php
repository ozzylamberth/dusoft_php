<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasMensajesSistema.class.php,
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



 class ConsultasMensajesSistema extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasMensajesSistema(){}

  


  
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarMensajeSistema($CodigoMensaje)
{

          $sql="
            select
            mensaje_id as codigo,
            descripcion,
            estado
            From
            inv_mensajes_producto
            where
            mensaje_id = '".$CodigoMensaje."';";
 
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
function ListadoMensajesSistema($offset)
{
        //$this->debug=true;
          $sql="
            select
            mensaje_id as codigo,
            descripcion,
            estado
            From
            inv_mensajes_producto
            
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "ORDER BY estado DESC,mensaje_id ";
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
		
	function InsertarMensajeSistema($Datos)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_mensajes_producto (";
    $sql .= "       mensaje_id     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['mensaje_sistema_id']."',";
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

    function ModificarMensajeSistema($datos)
	{
	  $sql  = "UPDATE inv_mensajes_producto ";
    $sql .= "SET ";
    $sql .= "descripcion = '".$datos['descripcion']."',";
    $sql .= "mensaje_id = '".$datos['mensaje_sistema_id']."'";
	  $sql .= " Where ";
    $sql .= "mensaje_id ='".$datos['mensaje_sistema_id_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	}
	
?>