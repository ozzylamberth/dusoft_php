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



 class ConsultasTopesDispensacion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasTopesDispensacion(){}

   
      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function Listar_TiposDispensacionSinAsignar($Empresa_Id)
{

          $sql="
            select 
                  td.tipo_dispensacion_id,
                  td.descripcion
                  from 
                  inv_farmacias_tiposdispensacion td
                  where
                  td.tipo_dispensacion_id Not In
                                          (
                                          select 
                                          tipo_dispensacion_id
                                          from
                                          inv_farmacias_x_tipodispensacion
                                          where
                                          empresa_id='".$Empresa_Id."' 
                                          ) 
                  and
                  td.estado ='1'
                  ";
 
 
 
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        

    $sql .= " order by td.descripcion ";
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
function Buscar_TiposDispensacionxFarmacia($Codigo)
{

 //$this->debug=true;
          $sql="
            select 
                  ftd.farmacia_dispensacion_id,
                  ftd.tope
                  from 
                    inv_farmacias_x_tipodispensacion ftd
                  where
                      ftd.farmacia_dispensacion_id='".$Codigo."';";
 
 
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
function Listar_TiposDispensacionAsignados($Empresa_Id,$offset)
{

 //$this->debug=true;
          $sql="
            select 
                  ftd.farmacia_dispensacion_id,
                  td.tipo_dispensacion_id,
                  td.descripcion,
                  ftd.tope,
                  ftd.estado
                  from 
                  inv_farmacias_tiposdispensacion td,
                  inv_farmacias_x_tipodispensacion ftd
                  where
                      ftd.empresa_id='".$Empresa_Id."' 
                      and
                      ftd.tipo_dispensacion_id=td.tipo_dispensacion_id
                      and
                      td.estado ='1'
                  ";
 
 
 
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        

    $sql .= " order by td.descripcion ";
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
		
	function InsertarAsignarDispensacionTope($Datos)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_farmacias_x_tipodispensacion (";
    $sql .= "       tipo_dispensacion_id     , ";
	  $sql .= "       empresa_id     , ";
    $sql .= "       farmacia_dispensacion_id     , ";
    $sql .= "       tope     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['tipo_dispensacion_id']."',";
      $sql .= "        '".$Datos['empresa_id']."',";
	  $sql .= "        '".$Datos['empresa_dispensacion_id']."',";
    $sql .= "        '".$Datos['tope']."',";
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

    function ModificarTopeDispensacion($datos)
	{
	  //$this->debug=true;
    $sql  = "UPDATE inv_farmacias_x_tipodispensacion ";
    $sql .= "SET ";
    $sql .= "tope = '".$datos['tope']."'";
	  $sql .= " Where ";
    $sql .= "farmacia_dispensacion_id ='".$datos['empresa_dispensacion_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	}
	
?>