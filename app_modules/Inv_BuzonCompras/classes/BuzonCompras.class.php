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



 class BuzonCompras extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function BuzonCompras(){}

  


  
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function ContrarMensajesBuzon($EmpresaId)
{

          $principal ="
            select
					*
            From
					inv_buzon_compras
            where
					empresa_id= '".$EmpresaId."'
					and
					sw_leido='0'
					and
					sw_estado='1'
            ";
 
		$sql = "SELECT COUNT(*) FROM(".$principal.") AS a;";
 
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
function ListadoMensajesBuzon($EmpresaId,$offset)
{
        //$this->debug=true;
          $sql="
                    select
					*
					From
					inv_buzon_compras
					where
					empresa_id= '".$EmpresaId."'
					and
					sw_estado='1'
            
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "ORDER BY fecha_mensaje DESC ";
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
  

function MensajeBuzon($buzon_compras_id)
{
        //$this->debug=true;
          $sql="
                    select
					*
					From
					inv_buzon_compras
					where
					buzon_compras_id= ".$buzon_compras_id."
            
            ";
 
 
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
*Funcion para Modificar Grupos
*/

    function BuzonMensajeLeido($buzon_compras_id)
	{
	  $sql  = "UPDATE inv_buzon_compras ";
    $sql .= "SET ";
    $sql .= "sw_leido = '1' ";
	  $sql .= " Where ";
    $sql .= " buzon_compras_id ='".$buzon_compras_id."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
		
		
		function LogLectura($buzon_compras_id,$EmpresaId)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_buzon_compras_log	 (";
    $sql .= "       buzon_compras_id     , ";
	  $sql .= "     empresa_id     , ";
    $sql .= "       fecha_lectura     , ";
    $sql .= "       log_id     , ";
    $sql .= "       usuario_id ";
    $sql .= "       )";
    
      $sql .= " VALUES( ";
      $sql .= "        ".$buzon_compras_id.",";
	  $sql .= "        '".$EmpresaId."',";
    $sql .= "        NOW(),";
    $sql .= "        DEFAULT,";
    $sql .= "        ".UserGetUID()." ";
    $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
	}
	
	  function CambioEstadoMensaje($buzon_compras_id)
	{
	  $sql  = "UPDATE inv_buzon_compras ";
	  $sql .= "SET ";
      $sql .= "sw_estado = '0' ";
	  $sql .= " Where ";
      $sql .= " buzon_compras_id ='".$buzon_compras_id."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	}
	
?>