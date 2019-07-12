<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : apoyod_tipos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class apoyod_tipos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function apoyod_tipos()
    {
      $this->primarykey = array("apoyod_tipo_id");
	$this->foreignkey = array("grupos_noqx_apoyod"=>
                              array("grupo_tipo_cargo" => "apoyod_tipo_id"));
        }
  }
?>