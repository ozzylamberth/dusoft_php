<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionEstadosTiposDocumentos.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ParametrizacionEstadosTiposDocumentos
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
 class ParametrizacionEstadosTiposDocumentos extends ConexionBD
 {
    /**
    * Contructor
    */  
	function ParametrizacionEstadosTiposDocumentos(){}
  
  /**
        * Funcion donde se consulta la informacion de los documentos 
       *
       * @return booleano
      */
  function BuscarDocumentos()
  {
    //$this->debug=true;
    $sql  = "SELECT	tipo_doc_general_id ";
    $sql .= "FROM		tipos_doc_generales ";
          
    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;

    $datos = array(); //Definiendo que va a ser un arreglo.
    
    while(!$rst->EOF) //Recorriendo el Vector;
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }
  
  /**
       * Funcion donde se consulta la informacion de los estados
       *
      * @return booleano
      */
  function BuscarEstados()
  {
    //$this->debug=true;
    $sql  = "SELECT	descripcion,abreviatura ";
    $sql .= "FROM		inv_estados_documentos ";
            
          
    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;

    $datos = array(); //Definiendo que va a ser un arreglo.
    
    while(!$rst->EOF) //Recorriendo el Vector;
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }
  
  /**
        * Funcion donde se consulta la informacion de la parametrizacion de los estados de un documento
       *
       * @param var  $empresa variable con la informacion de la empresa
       * @param var  $usuario variable con la informacion del usuario
       * @param var  $tipo_doc_general variable con la informacion del tipo de documento
       * @return booleano
      */
  function BuscarParameEstados($empresa,$usuario,$tipo_doc_general)
  {
    //$this->debug=true;
    $sql  = "SELECT	abreviatura,id_paramestadosdocum,tipo_doc_general_id ";
    $sql .= "FROM		paramestadosdocum ";
    $sql .= "WHERE  empresa_id='".$empresa."' ";
    $sql .= "AND    usuario_id=".$usuario." ";
    $sql .= "AND    tipo_doc_general_id='".$tipo_doc_general."' ";        
          
    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;

    $datos = array(); //Definiendo que va a ser un arreglo.
    
    while(!$rst->EOF) //Recorriendo el Vector;
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }
  
  /**
        * Funcion elimina en la tabla  paramestadosdocum
       *
       * @param var  $empresa variable con la informacion de la empresa
       * @param var  $tipo_doc_general variable con la informacion del tipo de documento
       * @return booleano
      */
  function EliminarParameEstados($empresa,$tipo_doc_general)
  {
      //$this->debug=true;
      $sql  = "DELETE FROM paramestadosdocum ";
      $sql .= "WHERE  tipo_doc_general_id='".$tipo_doc_general."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;

      $datos = array(); //Definiendo que va a ser un arreglo.
    
      while(!$rst->EOF) //Recorriendo el Vector;
      {
       $datos[] = $rst->GetRowAssoc($ToUpper = false);
       $rst->MoveNext();
      }
      $rst->Close();
      return $datos;    
  }
  
  /**
        * Funcion donde se almacena la informacion de los estados del documento
       *
       * @param var  $tipo_doc_general variable con la informacion del tipo de documento
       * @param var  $empresa variable con la informacion de la empresa
      * @param var  $estado variable con la informacion del estado
       * @return booleano
      */
  function AgregarEstados($tipo_doc_general,$empresa,$estado,$permisos)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
    
    $sql = "INSERT INTO paramestadosdocum( ";
    $sql .= "            id_paramestadosdocum, ";
    $sql .= "            tipo_doc_general_id, ";
    $sql .= "            abreviatura, ";
    $sql .= "            usuario_id, ";
    $sql .= "            fecha_registro, ";
    $sql .= "            empresa_id ";
    $sql .= ")VALUES    (";
    $sql .= "           default, ";
    $sql .= "           '".$tipo_doc_general."', ";
    $sql .= "           '".$estado."', ";  
    $sql .= "           ".UserGetUID().", ";
    $sql .= "           NOW(), ";
    $sql .= "           '".$empresa."' )";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
 }
?>