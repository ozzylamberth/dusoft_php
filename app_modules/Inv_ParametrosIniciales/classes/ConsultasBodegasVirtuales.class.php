<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasBodegasVirtuales.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ConsultasBodegasVirtuales
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class ConsultasBodegasVirtuales extends ConexionBD
{
  /**
    * Contructor
    */  
	function ConsultasBodegasVirtuales(){}
  
  /**
        * Funcion donde se consulta todos las bodegas
       *
       * @return booleano
      */
  function ListarBodegas($offset)
  {
    //$this->debug=true;
    $sql  = "SELECT	a.bodega,a.descripcion,b.descripcion as descrip_bod,a.sw_virtual,b.departamento ";
    $sql .= "FROM		bodegas as a, ";
    $sql .= "  		  departamentos as b ";
    $sql .= "WHERE  a.departamento=b.departamento ";
    
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
       * Funcion donde se almacena la informacion las bodegas virtuales
       *
       * @param  var $bodega contiene la bodega
       * @param  var $departamento contiene el departamento
       * @param  var $sw_virtual contiene el sw si es virtual
       * @return booleano
      */
  function AgregarBodegaVirtual($bodega,$departamento,$sw_virtual)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
    
    $sql  = "UPDATE   bodegas ";
    $sql .= "SET      sw_virtual = '".$sw_virtual."' ";
    $sql .= "WHERE    bodega='".$bodega."' AND departamento='".$departamento."' ";
    
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