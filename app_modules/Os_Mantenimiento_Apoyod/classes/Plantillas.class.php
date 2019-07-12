<?php
  /**
  * Clase : Plantillas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Plantillas extends ConexionBD 
  {
    /** Contructor de la clase */
    function Plantillas(){}
    /** 
    * Funcion donde se consulta si una plantilla ya existe o no
    *
    * @param array $datos Datos de la plantilla
    *
    * @return mixed
    */
    function DatosPlantilla($datos)
    {
      $tecnica = explode("||//",$datos['tecnica']);
    
      $sql  = "SELECT COUNT(*) AS cantidad ";
      $sql .= "FROM   lab_plantilla2 ";
      $sql .= "WHERE  lab_examen_opcion_id = '".$datos['opcion_id']."' ";
      $sql .= "AND    lab_examen_id =  ".$datos['lab_examen']."  " ;
      $sql .= "AND    cargo = '".$datos['cargo']."' " ;
      $sql .= "AND    tecnica_id =  ".$tecnica[0]." ";   
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $cantidad =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $cantidad['cantidad'];
    }
    /**
    * Funcion encargada de hacer el insert de la plantilla2
    *
    * @param array $datos Datos de la plantilla    
    * 
    * @return boolean
    */
    function InsertarPlantilla($datos)
    {
      $tecnica = explode("||//",$datos['tecnica']);
      $this->ConexionTransaccion();
   
      $sql  = "INSERT INTO lab_plantilla2( ";
      $sql .= "   cargo,";
      $sql .= "   tecnica_id,";
      $sql .= "   lab_examen_id,";
      $sql .= "   lab_examen_opcion_id, ";
      $sql .= "   unidades,";
      $sql .= "   normalidades,";
      $sql .= " 	apoyod_cargos_tecnicas_id ";
      $sql .= ") ";
      $sql .= "VALUES(";
      $sql .= "   '".$datos['cargo']."', ";
      $sql .= "    ".$tecnica[0].",";
      $sql .= "    ".$datos['lab_examen']." ,";    
      $sql .= "   '".$datos['opcion_id']."', ";
      $sql .= "   '".$datos['unidades']."', ";
      $sql .= "   '".$datos['normalidades']."', ";
      $sql .= "    ".$tecnica[1]." ";
      $sql .= " ) ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "INSERT INTO opciones_lab_plantilla2 ";
      $sql .= "(";
      $sql .= "   opcion_id,"; 	 	
      $sql .= "   descripcion, ";
      $sql .= "   cargo, ";
      $sql .= "   lab_examen_id , ";		
      $sql .= "   lab_examen_opcion_id, "; 	
      $sql .= "   tecnica_id ";
      $sql .= ") ";
      $sql .= "VALUES ";
      $sql .= "( ";
      $sql .= "   DEFAULT, ";
      $sql .= "   '".$datos['opcion_des']."', ";
      $sql .= "   '".$datos['cargo']."', ";
      $sql .= "    ".$datos['lab_examen']." ,";
      $sql .= "   '".$datos['opcion_id']."', ";
      $sql .= "    ".$tecnica[0]." ";
      $sql .= ") ";
    
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
    
      $this->Commit();
      return true;
    }
    /**
    * Funcion encargada de hacer el insert de la opcion de la plantilla2
    *
    * @param array $datos Datos de la plantilla    
    * 
    * @return boolean
    */
    function InsertarOpcionPlantilla($datos)
    {
      $tecnica = explode("||//",$datos['tecnica']);
      
      $sql  = "INSERT INTO opciones_lab_plantilla2 ";
      $sql .= "(";
      $sql .= "   opcion_id,"; 	 	
      $sql .= "   descripcion, ";
      $sql .= "   cargo, ";
      $sql .= "   lab_examen_id , ";		
      $sql .= "   lab_examen_opcion_id, "; 	
      $sql .= "   tecnica_id ";
      $sql .= ") ";
      $sql .= "VALUES ";
      $sql .= "( ";
      $sql .= "   DEFAULT, ";
      $sql .= "   '".$datos['opcion_des']."', ";
      $sql .= "   '".$datos['cargo']."', ";
      $sql .= "    ".$datos['lab_examen']." ,";
      $sql .= "   '".$datos['opcion_id']."', ";
      $sql .= "    ".$tecnica[0]."  ";
      $sql .= ") ";
    
      if(!$this->ConexionBaseDatos($sql)) 
        return false;
      
      return true;
    } 
    function BuscarParaEditar($cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id)
    {
    $tecnica = explode("||//",$datos['tecnica']);
      $sql  = " SELECT *";
      $sql .= " FROM lab_plantilla2 ";
      $sql .= " WHERE cargo = '".$cargo."' ";
      $sql .= " AND lab_examen_opcion_id = '".$lab_examen_opcion_id."' ";
      $sql .= " AND lab_examen_id =  ".$lab_examen_id." ";
      $sql .= " AND tecnica_id =  ".$tecnica_id." ";
      //if(!$this->ConexionBaseDatos($sql)) 
      //    return false;
     if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
     } 
     
     function BucarOpcionesEditar($cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id)
     {
     
      $sql  = " SELECT * ";
      $sql .= " FROM opciones_lab_plantilla2 ";
      $sql .= " WHERE cargo = '".$cargo."' ";
      $sql .= " AND lab_examen_opcion_id = '".$lab_examen_opcion_id."' ";
      $sql .= " AND lab_examen_id =  ".$lab_examen_id." ";
      $sql .= " AND tecnica_id =  ".$tecnica_id." ";
      //if(!$this->ConexionBaseDatos($sql)) 
      //    return false;
     if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
     }
     
     function EliminarOpcion($opcion_id, $cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id)
     {
      
      $sql  = "DELETE FROM opciones_lab_plantilla2 ";  
      $sql .= "  WHERE opcion_id= ".$opcion_id." ";
      $sql .= "  AND cargo='".$cargo."' ";
      $sql .= "  AND lab_examen_id= ".$lab_examen_id." ";
      $sql .= "  AND lab_examen_opcion_id='".$lab_examen_opcion_id."' ";
      $sql .= "  AND tecnica_id=".$tecnica_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return $datos;
     }

     function ActualizacionPlan ($datos, $cargo, $lab_examen_id, $lab_examen_opcion_id, $tecnica_id)
     {
      //$this-> debug = true;
      
      $sql  = " UPDATE lab_plantilla2 ";
      $sql .= " SET unidades = '".$datos['unidades']."', ";  
      $sql .= "    normalidades = '".$datos['normalidades']."' "; 
      $sql .= " WHERE lab_examen_opcion_id ='".$lab_examen_opcion_id."' "; 
      $sql .= " AND lab_examen_id = ".$lab_examen_id." "; 
      $sql .= " AND cargo = '".$cargo."' "; 
      $sql .= " AND tecnica_id = ".$tecnica_id." "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return $datos;
      }
      
    function InsertarSolodescPlantilla($datos,$cargo, $lab_examen_id, $lab_examen_opcion_id, $tecnica_id )
    {
      //$this-> debug = true;
      
      $sql  = "INSERT INTO opciones_lab_plantilla2 ";
      $sql .= "(";
      $sql .= "   opcion_id,"; 	 	
      $sql .= "   descripcion, ";
      $sql .= "   cargo, ";
      $sql .= "   lab_examen_id , ";		
      $sql .= "   lab_examen_opcion_id, "; 	
      $sql .= "   tecnica_id ";
      $sql .= ") ";
      $sql .= "VALUES ";
      $sql .= "( ";
      $sql .= "   DEFAULT, ";
      $sql .= "   '".$datos['opcion_des']."', ";
      $sql .= "   '".$cargo."', ";
      $sql .= "    ".$lab_examen_id." ,";
      $sql .= "   '".$lab_examen_opcion_id."', ";
      $sql .= "    ".$tecnica_id."  ";
      $sql .= ") ";
    
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
    
      return true;
    } 
  }/*fin de clase*/
?>