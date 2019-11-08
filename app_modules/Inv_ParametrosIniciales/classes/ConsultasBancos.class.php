<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasNovedadesDevolucion.class.php,
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



 class ConsultasBancos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasBancos(){}

  
  
  
  
  function BusquedaBancos($Banco,$Descripcion,$offset)
{
  
  $sql="
            select
            banco as codigo,
            descripcion,
            estado
            From
            bancos
            where
            banco LIKE '%".$Banco."%'
            and
            descripcion LIKE '%".$Descripcion."%'
            ";
 
 
 if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY estado DESC,descripcion ";
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
  
  
  
  
  
  
  
  
  
  
  


  
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarBanco($Banco)
{

          $sql="
            select
            banco as codigo,
            descripcion,
            telefono,
            direccion,
            tipo_mpio_id,
            tipo_dpto_id,
            estado
            From
            bancos
            where
            banco = '".$Banco."';
            ";
 
 
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
function ListadoBancos($offset)
{

          $sql="
            select
            banco as codigo,
            descripcion,
            estado
            From
            bancos
            
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY estado DESC,banco ";
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
		
	function InsertarBanco($Datos)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO bancos (";
    $sql .= "       banco     , ";
	  $sql .= "       descripcion     , ";
    $sql .= "       telefono     , ";
    $sql .= "       direccion     , ";
    $sql .= "       tipo_mpio_id     , ";
    $sql .= "       tipo_dpto_id     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['banco']."',";
	  $sql .= "        '".$Datos['descripcion']."',";
    $sql .= "        '".$Datos['telefono']."',";
    $sql .= "        '".$Datos['direccion']."',";
    $sql .= "        '".$Datos['tipo_mpio_id']."',";
    $sql .= "        '".$Datos['tipo_depto_id']."',";
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

    function ModificarBanco($datos)
	{
	  $sql  = "UPDATE bancos ";
    $sql .= "SET ";
    $sql .= "descripcion = '".$datos['descripcion']."',";
    $sql .= "telefono = '".$datos['telefono']."',";
    $sql .= "direccion = '".$datos['direccion']."',";
    $sql .= "tipo_dpto_id = '".$datos['tipo_depto_id']."',";
    $sql .= "tipo_mpio_id = '".$datos['tipo_mpio_id']."',";
    $sql .= "banco = '".$datos['banco']."'";
	  $sql .= " Where ";
    $sql .= "banco ='".$datos['banco_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  

  
       /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function ListarDepartamentos($tipo_pais_id)
{

          $sql="
            select
            tipo_dpto_id,
            tipo_pais_id,
            departamento
            From
            tipo_dptos
            where
            tipo_pais_id ='".$tipo_pais_id."';";
 
 
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
  
  
  function ListarMunicipios($tipo_pais_id,$tipo_dpto_id)
{

          $sql="
            select
            tipo_mpio_id,
            municipio
            From
            tipo_mpios
            where
            tipo_pais_id ='".$tipo_pais_id."'
            and
            tipo_dpto_id ='".$tipo_dpto_id."';";
 
 
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
  
  
  
  
  
  
  
  }
?>