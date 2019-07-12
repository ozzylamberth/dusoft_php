<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionTomaFisica.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ParametrizacionTomaFisica
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
 class ParametrizacionTomaFisica extends ConexionBD
 {
  /**
    * Contructor
    */  
	function ParametrizacionTomaFisica(){}
  
  /**
        * Funcion donde se consulta la informacion de la parametrizacion de la toma fisica
       *
       * @return booleano
      */
  function Buscarparamtomafisica()
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		paramtomafisica ";
          
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
       * @param  var $cantidad contiene la cantidad
      * @param  var $mayor_rotacion contiene el sw de mayor_rotacion
      * @param  var $mayor_costo contiene el sw de mayor_costo
      * @param  var $lunes contiene el sw de lunes
      * @param  var $martes contiene el sw de martes
      * @param  var $miercoles contiene el sw de miercoles
      * @param  var $jueves contiene el sw de jueves
      * @param  var $viernes contiene el sw de viernes
      * @param  var $sabado contiene el sw de sabado
      * @param  var $domingo contiene el sw de domingo
      * @param  var $aleatorio contiene el sw de aleatorio
      * @return booleano
      */
           
  function AgregarParamTomaFisica($cantidad,$mayor_rotacion,$mayor_costo,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo,$aleatorio)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
   
    $sql = "INSERT INTO paramtomafisica( ";
    $sql .= "            id_paramtomafisica, ";
    $sql .= "            cantidad, ";
    $sql .= "            sw_mayor_rotacion, ";
    $sql .= "            sw_mayor_costo, ";
    $sql .= "            sw_lunes, ";
    $sql .= "            sw_martes, ";
    $sql .= "            sw_miercoles, ";
    $sql .= "            sw_jueves, ";
    $sql .= "            sw_viernes, ";
    $sql .= "            sw_sabado, ";
    $sql .= "            sw_domingo, ";
    $sql .= "            usuario_id, ";
    $sql .= "            fecha_registro, ";
    $sql .= "            sw_activado, " ;
    $sql .= "            sw_aleatorio " ;
    $sql .= ")VALUES    (";
    $sql .= "           default, ";
    $sql .= "           ".$cantidad.", ";
    $sql .= "           '".$mayor_rotacion."', "; 
    $sql .= "           '".$mayor_costo."', "; 
    $sql .= "           '".$lunes."', "; 
    $sql .= "           '".$martes."', "; 
    $sql .= "           '".$miercoles."', "; 
    $sql .= "           '".$jueves."', "; 
    $sql .= "           '".$viernes."', "; 
    $sql .= "           '".$sabado."', "; 
    $sql .= "           '".$domingo."', "; 
    $sql .= "           ".UserGetUID().", ";
    $sql .= "           NOW(), '0', '".$aleatorio."'   ) ";
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
   /**
      * Funcion donde actualiza el estado de la activacion
      *
      * @param  var $id_paramtomafisica contiene la id_paramtomafisica
      * @param  var $activarlo contiene la activacion
      * @return booleano
      */
  function ActualizarActivar($id_paramtomafisica,$activarlo)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
    $sql  = "UPDATE paramtomafisica ";
    $sql .= "SET    sw_activado = '".$activarlo."'  "; 
    $sql .= "WHERE  id_paramtomafisica= ".$id_paramtomafisica." ";
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      echo $this->mensajeDeError;
        return false;
        
    }
    $this->Commit();          
  }
 }
?>