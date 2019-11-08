<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasParamTorresP.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ConsultasParamTorresP
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class ConsultasParamTorresP extends ConexionBD
{
  /**
    * Contructor
    */  
	function ConsultasParamTorresP(){}
  
  /**
        * Funcion donde se consulta todos las productos en existencias bodegas
       *
        * @param  var $empresa contiene la empresa
       * @return booleano
      */
  function ListarProductos($empresa,$offset)
  {
    //$this->debug=true;
    $sql  = "SELECT	a.*,b.descripcion as descripcion_prod ";
    $sql .= "FROM		existencias_bodegas a, ";
    $sql .= "  		  inventarios_productos as b ";
    $sql .= "WHERE  a.empresa_id='".$empresa."' ";
    $sql .= "AND    a.codigo_producto=b.codigo_producto ";
   
    
    
     $cont="select COUNT(*) from (".$sql.") AS A";
     $this->ProcesarSqlConteo($cont,$offset);
     $sql .= "ORDER BY descripcion ";
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     if(!$rst = $this->ConexionBaseDatos($sql))        return false;
     $datos = array();
     while (!$rst->EOF)
     {
       $datos[] = $rst->GetRowAssoc($ToUpper = false);
       $rst->MoveNext();
     }
     $rst->Close();
     //print_r($datos);
     return $datos;
  }
  
  /**
        * Funcion donde consulta parametrizacion de la torre de cada producto y su dueño
       *
       * @return booleano
      */
  function Buscarparamprod($empresa_id,$codigo_producto)
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		param_torreproducto ";
    $sql .= "WHERE	empresa_id='".$empresa_id."' ";
    $sql .= "AND	  codigo_producto='".$codigo_producto."' ";
    
    if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    
  }
  /**
        * Funcion donde consulta las torres y el dueños
       *
       * @return booleano
      */
   function BuscarTorres()
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		param_torreproducto ";
          
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
       * Funcion donde se almacena la informacion las torres
       *
       * @param  var $codigo_producto contiene la bodega
       * @param  var $descripcion contiene el departamento
       * @param  var $empresa_id contiene la empresa
       * @param  var $torre contiene la torre
       * @param  var $due_torre contiene el dueño de la torre
       * @return booleano
      */
  function AgregarTorreP($codigo_producto,$descripcion,$empresa_id,$torre,$due_torre)
  {
    $this->debug=true;
    $this->ConexionTransaccion();
    $sql = "INSERT INTO param_torreproducto( ";
    $sql .= "            id_param_torreproducto, ";
    $sql .= "            codigo_producto, ";
    $sql .= "            descripcion, ";
    $sql .= "            empresa_id, ";
    $sql .= "            torre, ";
    $sql .= "            dueno_torre, ";
    $sql .= "            usuario_registro, ";
    $sql .= "            fecha_registro";
    $sql .= ")VALUES    (";
    $sql .= "           default, ";
    $sql .= "           '".$codigo_producto."', ";
    $sql .= "           '".$descripcion."', ";
    $sql .= "           '".$empresa_id."', ";
    $sql .= "           '".$torre."', ";
    $sql .= "           '".$due_torre."', ";
    $sql .= "           ".UserGetUID().", ";
    $sql .= "           NOW() ) ;";
    
    $sql .= "UPDATE   existencias_bodegas ";
    $sql .= "SET      local_prod = '".$torre."' ";
    $sql .= "WHERE    empresa_id='".$empresa_id."' AND codigo_producto='".$codigo_producto."' ";
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
  /**
       * Funcion donde se actualiza la parametrizacion de la torre y dueño de la torre
       *
       * @param  var $codigo_producto contiene la bodega
       * @param  var $descripcion contiene el departamento
       * @param  var $empresa_id contiene la empresa
       * @param  var $torre contiene la torre
       * @param  var $due_torre contiene el dueño de la torre
       * @return booleano
      */
  function ActuParamT($codigo_producto,$descripcion,$empresa_id,$torre,$due_torre)
  {
    $this->debug=true;
    $this->ConexionTransaccion();
    $sql .= "UPDATE   param_torreproducto ";
    $sql .= "SET      torre = '".$torre."',dueno_torre='".$due_torre."' ";
    $sql .= "WHERE    empresa_id='".$empresa_id."' AND codigo_producto='".$codigo_producto."' ;";
    
    $sql .= "UPDATE   existencias_bodegas ";
    $sql .= "SET      local_prod = '".$torre."' ";
    $sql .= "WHERE    empresa_id='".$empresa_id."' AND codigo_producto='".$codigo_producto."' ";
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