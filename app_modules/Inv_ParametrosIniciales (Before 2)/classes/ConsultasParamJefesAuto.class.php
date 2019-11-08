<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasParamJefesAuto.class.php
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
class ConsultasParamJefesAuto extends ConexionBD
{
  /**
    * Contructor
    */  
	function ConsultasParamJefesAuto(){}
  
 /*
        * Funcion donde consulta parametrizacion de la torre de cada producto y su dueño
       *
       * @return booleano
      */
  function Buscarparamprod($empresa_id)
  {
   //$this->debug=true;
   $sql  = "SELECT	* ";
    $sql .= "FROM		salidas_productos_tmp as a, vald_jefedoctmp as b ";
    $sql .= "WHERE	b.empresa_id='".$empresa_id."' ";
    $sql .= "AND	    a.doc_tmp_id=b.doc_tmp_id ";
    //$sql .= "WHERE	empresa_id='".$empresa_id."' ";
    //$sql .= "AND	  doc_tmp_id='".$doc_tmp_id."' ";
    //print_r($sql);
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
  
  function BuscarparamUsuarios()
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		parame_usuariosjefebodcon ";
    $sql .= "WHERE	activo='1' ";
    //$sql .= "AND	  doc_tmp_id='".$doc_tmp_id."' ";
    //print_r($sql);
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
  
  /*
        * Funcion donde consulta parametrizacion de la torre de cada producto y su dueño
       *
       * @return booleano
      */
  function BuscarparDoc_Tmp($empresa_id,$doc_tmp_id)
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		vald_jefedoctmp ";
    $sql .= "WHERE	  doc_tmp_id=".$doc_tmp_id." ";
    //print_r($sql);
    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;

    $datos = array(); //Definiendo que va a ser un arreglo.
    
    while(!$rst->EOF) //Recorriendo el Vector;
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    //print_r($datos);
    return $datos;
  }
  
  function GuardarParGrabar($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
  {
    //$this->debug=true;
     $this->ConexionTransaccion();
     $sql = " INSERT INTO  vald_jefedoctmp(
                           id_vald_jefedoctmp,              
                           doc_tmp_id,	
                           sw_jefebodega,
                           sw_jefecontroli,
                           empresa_id,
                           usuario_registro,
                           fecha_registro)
               VALUES     (default,
                           '".$doc_tmp_id."',
                           '".$sw_jefebodega."',
                           '".$sw_jefecontroli."',
                           '".$empresa_id."',
                           ".UserGetUID().",
                           NOW() )";
     if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
   function ActuParam($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
  {
    //$this->debug=true;
     $sql = " UPDATE vald_jefedoctmp
              SET    sw_jefebodega='".$sw_jefebodega."',sw_jefecontroli='".$sw_jefecontroli."'             
              WHERE  doc_tmp_id='".$doc_tmp_id."'
              AND    empresa_id ='".$empresa_id."';
              ";
              
      
              
    //print_r($sql);
    if(!$resultado = $this->ConexionBaseDatos($sql))
    {
      $cad="Operacion Invalida";
      return false;//$cad;
    } 
    
    return true;
  }
  
}
?>