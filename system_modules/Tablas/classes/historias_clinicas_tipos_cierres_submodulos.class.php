<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : historias_clinicas_tipos_cierres_submodulos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class historias_clinicas_tipos_cierres_submodulos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function historias_clinicas_tipos_cierres_submodulos()
    {
      $this->primarykey = array("historia_clinica_tipo_cierre_id","submodulo");
	$this->foreignkey = array("historias_clinicas_tipos_cierres"=>
                              array("historia_clinica_tipo_cierre_id" => "historia_clinica_tipo_cierre_id"),
					"system_hc_submodulos"=>
                              array("submodulo" => "submodulo"),
					"tipo_sexo"=>
                              array("sexo_id" => "sexo_id"));
	
	
	
        }
  }
?>