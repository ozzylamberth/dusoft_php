<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tipos_camas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipos_camas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipos_camas()
    {
      $this->primarykey = array("tipo_cama_id");
	$this->foreignkey = array("tipos_clases_camas"=>
                              array("tipo_clase_cama_id" => "tipo_clase_cama_id"));
	
    }
  }
?>