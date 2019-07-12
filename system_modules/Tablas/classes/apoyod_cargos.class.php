<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id:  apoyod_cargos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : apoyod_cargos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class apoyod_cargos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function apoyod_cargos()
    {
      $this->primarykey = array("cargo");
      $this->foreignkey = array("cups"=>
                              array("cargo" => "cargo"),
                              "grupos_noqx_apoyod"=>
                              array("grupo_tipo_cargo" => "apoyod_tipo_id"),
                              "tipo_sexo"=>
                              array("sexo_id" => "sexo_id"),
                        );
    }
  }
?>