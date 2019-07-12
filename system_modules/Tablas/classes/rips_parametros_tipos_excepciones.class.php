<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : rips_parametros_tipos_excepciones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class rips_parametros_tipos_excepciones extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function rips_parametros_tipos_excepciones()
    {
      $this->primarykey = array("empresa_id","tarifario_id","cargo","rips_tipo_id");
      $this->foreignkey = array("tarifarios_detalle"=>
                              array("cargo" => "cargo"),
					"tarifarios"=>
                              array("tarifario_id" => "tarifario_id"),
					"rips_tipos"=>
                              array("rips_tipo_id" => "rips_tipo_id"),
					"empresas"=>
                              array("empresa_id" => "empresa_id"),
                          );
    }
  }
?>