<?php
  /*	
  * Clase :  userpermisos_tablas
	
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class	userpermisos_tablas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function  userpermisos_tablas()
    {
      $this->primarykey = array("usuario_id");
      $this->foreignkey = array("system_usuarios"=>
                          array("usuario_id" => "usuario_id"),
                         );
    }
  }
?>