<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tarifarios
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tarifarios extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tarifarios()
    {
      $this->primarykey = array("tarifario_id");
       $this->foreignkey = array("tipos_tarifarios"=>
                              array("tipo_tarifario_id" => "tipo_tarifario_id"),
                          );
    }
  }
?>